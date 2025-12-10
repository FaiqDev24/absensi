<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['classRoom', 'user'])->get();
        return view('admin.student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classRooms = ClassRoom::all();
        return view('admin.student.create', compact('classRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'nis' => 'required|unique:students,nis',
            'name' => 'required',
            'password' => 'nullable|min:8',
            'gender' => 'required|in:L,P',
            'class_room_id' => 'nullable|exists:class_rooms,id',
        ]);

        // try {
        //     DB::beginTransaction();

            // 1. Create User
            // Generate dummy email from NIS
            $email = $request->nis . '@gmail.com';
            $user = User::create([
                'name' => $request->name,
                'username' => $request->nis, // NIS as username
                'email' => $email,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);

            // 2. Create Student
            $student = Student::create([
                'id_user' => $user->id,
                'nis' => $request->nis,
                'class_room_id' => $request->class_room_id,
                'gender' => $request->gender,
            ]);

            // DB::commit();

            return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan!');
        // } catch (\Exception $e) {
            // DB::rollBack();
            // return redirect()->back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage())->withInput();
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student, $id)
    {
        $student = Student::with('user')->find($id);
        $classRooms = ClassRoom::all();
        return view('admin.student.edit', compact('student', 'classRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nis' => 'required|unique:students,nis,' . $id,
            'name' => 'required',
            'password' => 'nullable|min:8',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'class_room_id' => 'nullable|exists:class_rooms,id',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::findOrFail($id);
            $user = $student->user;

            // 1. Update Student
            $student->update([
                'nis' => $request->nis,
                'gender' => $request->gender,
                'class_room_id' => $request->class_room_id,
            ]);

            // 2. Update User
            $userData = [
                'name' => $request->name,
                'username' => $request->nis, // Sync username with NIS
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            // Sync email if NIS changed
            if ($user->username != $request->nis) {
                 $userData['email'] = $request->nis . '@student.school.id';
            }

            $user->update($userData);

            DB::commit();

            return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui siswa: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        if ($request->has('detach')) {
            $classId = $student->class_room_id;
            $student->update(['class_room_id' => null]);
            return redirect()->route('admin.classrooms.show', ['id' => $classId])->with('success', 'Siswa berhasil dikeluarkan dari kelas!');
        }

        // Delete User (Cascade will handle Student deletion if set in DB, but better explicit or use Model events)
        // Since we have soft deletes on both, deleting user soft depends on logic.
        // Let's delete user -> this soft deletes user.
        // Student also has SoftDeletes trait.
        
        $user = $student->user;
        if ($user) {
            $user->delete(); // Soft delete User
        }
        $student->delete(); // Soft delete Student explicitly to be safe

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus!');
    }

    public function export() {
        $fileName = 'students-export.xlsx';
        return Excel::download(new StudentExport, $fileName);
    }

    public function trash()
    {
        $students = Student::onlyTrashed()->with('user')->get();
        return view('admin.student.trash', compact('students'));
    }

    public function restore($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->restore();
        
        if ($student->user()->trashed()) {
            $student->user()->restore();
        }

        return redirect()->route('admin.students.trash')
            ->with('success', 'Data siswa berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $user = $student->user()->withTrashed()->first();
        
        $student->forceDelete();
        if ($user) {
             $user->forceDelete();
        }

        return redirect()->route('admin.students.trash')
            ->with('success', 'Data siswa berhasil dihapus permanen!');
    }

}
