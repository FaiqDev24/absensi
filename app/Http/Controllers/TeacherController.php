<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with('subjects','user')->get();
        return view('admin.teacher.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'nip' => 'required|min:8|unique:teachers',
            'name' => 'required',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Email wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.min' => 'NIP harus terdiri dari minimal 8 karakter.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'name.required' => 'Nama wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
        ]);
        $createDataUser = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'teacher'
        ]);

        $createData = Teacher::create([
            'id_user' => $createDataUser->id,
            'nip' => $request->nip,
        ]);


        if ($createData) {
            return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil ditambahkan.');
        } else {
            return redirect()->route('admin.teachers.index')->with('error', 'Data Guru gagal ditambahkan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $teacher = Teacher::find($id);
        $subject = Subject::get();
        return view('admin.teacher.edit', compact('teacher', 'subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'id_user' => 'required',
        //     'nip' => 'required|min:8|unique:teachers,nip,'.$id,
        //     'username' => 'required',
        //     'password' => 'required|min:8',
        // ]);

        $teacher = Teacher::find($id);
        $user = User::find($teacher->id_user);
        $user->name = $request->name;
        $user->save();
        // $createDataUser = User::update([
        //     'email' => $request->email,
        //     'name' => $request->name,
        //     'password' => Hash::make($request->password),
        //     'role' => 'teacher'
        // ]);

        // $createData = Teacher::update([
        //     'id_user' => $createDataUsSer->id,
        //     'nip' => $request->nip,
        // ]);
        $teacher->update([
            'id_user' => $user->id,
            'nip' => $request->nip,
            'subject_id' => $request->subject_id,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Subject::where('teacher_id', $id)->update(['teacher_id' => null]);
        $teacher = Teacher::where('id', $id);
        $teacher->delete();
        //$teacher->forceDelete() : untuk menghapus data secara permanen baik ditampilan dan di database.
        return redirect()->route('admin.teachers.index')->with('success', 'Berhasil menghapus data guru!');
    }

    public function trash()
    {
        $teachers = Teacher::onlyTrashed()->get();
        return view('admin.teacher.trash', compact('teachers'));
    }

    public function restore($id)
    {
        $teacher = Teacher::onlyTrashed()->findOrFail($id);
        $teacher->restore();

        return redirect()->route('admin.teachers.trash')->with('success', 'Data guru berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $teacher = Teacher::onlyTrashed()->findOrFail($id);
        $teacher->forceDelete();

        return redirect()->route('admin.teachers.trash')->with('success', 'Data guru berhasil dihapus permanen!');
    }

}
