<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentExport;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('classRoom')->get();
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
            'username' => 'required',
            'password' => 'required|min:8',
            'gender' => 'required|in:L,P',
            'class_room_id' => 'required|exists:class_rooms,id',
            'grade' => 'nullable',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'gender.required' => 'Gender wajib dipilih.',
            'class_room_id.required' => 'Kelas wajib dipilih.',
        ]);

        $createData = Student::create([
            'nis' => $request->nis,
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'class_room_id' => $request->class_room_id,
            'grade' => $request->grade,
        ]);

        if ($createData) {
            return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan!');
        } else {
            return redirect()->route('admin.students.index')->with('error', 'Data siswa gagal ditambahkan!');
        }
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
        $student = Student::find($id);
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
            'username' => 'required',
            'password' => 'nullable|min:8',
            'gender' => 'required|in:L,P',
            'class_room_id' => 'required|exists:class_rooms,id',
            'grade' => 'nullable',
        ]);

        $student = Student::where('id', $id);
        $student->update([
            'nis' => $request->nis,
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'class_room_id' => $request->class_room_id,
            'grade' => $request->grade,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $student = Student::find($id);

        if ($request->has('detach')) {
            $classId = $student->class_room_id;
            $student->update(['class_room_id' => null]);

            return redirect()->route('admin.classrooms.show', ['id' => $classId])->with('success', 'Siswa berhasil dikeluarkan dari kelas!');
        }

        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus!');

    }

    public function export() {
        $fileName = 'students-export.xlsx';
        return Excel::download(new StudentExport, $fileName);
    }

    public function trash()
    {
        $students = Student::onlyTrashed()->get();
        return view('admin.student.trash', compact('students'));
    }

    public function restore($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->route('admin.students.trash')
            ->with('success', 'Data siswa berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->forceDelete();

        return redirect()->route('admin.students.trash')
            ->with('success', 'Data siswa berhasil dihapus permanen!');
    }

}
