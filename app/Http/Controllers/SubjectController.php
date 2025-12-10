<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::with('teachers')->get();
        return view('admin.subject.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::all();
        return view('admin.subject.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255|unique:subjects,name',
        //     'teacher_id' => 'nullable|exists:teachers,id'
        // ], [
        //     'name.required' => 'Nama mata pelajaran harus ada!',
        //     'teacher_id' => 'Nama guru harus ada!'
        // ]);

        // $createData = Subject::create([
        //     'name' => $request->name,
        //     'teacher_id' => $request->teacher_id,
        // ]);

        // if ($createData) {
        //     return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan!');
        // } else {
        //     return redirect()->back()->with('error', 'Mata pelajaran gagal ditambahkan!');
        // }

        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'teacher_ids' => 'nullable|array',
        ], [
            'name.required' => 'Nama mata pelajaran harus ada!',
        ]);

        // buat subject baru
        $subject = Subject::create([
            'name' => $request->name,
        ]);

        // sync teachers
        if ($request->has('teacher_ids')) {
            $subject->teachers()->attach($request->teacher_ids);
        }
        
        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subject = Subject::with('teachers')->findOrFail($id);
        $teachers = Teacher::all();

        return view('admin.subject.edit', compact('subject', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255|unique:subjects,name,' . $id,
        //     'teacher_id' => 'nullable|exists:teachers,id'
        // ]);

        // $subject = Subject::where('id', $id);
        // $subject->update([
        //     'name' => $request->name,
        //     'teacher_id' => $request->teacher_id,
        // ]);

        // return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui!');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_ids' => 'nullable|array',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update(['name' => $validated['name']]);

        // Sync teachers
        if (isset($validated['teacher_ids'])) {
            $subject->teachers()->sync($validated['teacher_ids']);
        } else {
            $subject->teachers()->detach();
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $subject = Subject::where('id', $id);
        // $subject->delete();

        // return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus!');

        $subject = Subject::findOrFail($id);

        // hapus relasi guru
        Teacher::where('subject_id', $subject->id)->update(['subject_id' => null]);

        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}
