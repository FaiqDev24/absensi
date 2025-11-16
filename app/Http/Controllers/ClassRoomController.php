<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classRooms = ClassRoom::all();
        return view('admin.classroom.index', compact('classRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.classroom.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_rooms,name',
        ]);

        $createData = ClassRoom::create([
            'name' => $request->name,
        ]);

        if ($createData) {
            return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Kelas gagal ditambahkan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $classRoom = ClassRoom::with(['students' => function($q) {
            $q->orderBy('name', 'asc');
        }])->find($id);
        return view('admin.classroom.detail', compact('classRoom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $classRoom = ClassRoom::find($id);
        return view('admin.classroom.edit', compact('classRoom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_rooms,name,' . $id,
        ]);

        $classRoom = ClassRoom::find($id);
        $classRoom->update($request->only('name'));

        return redirect()->route('admin.classrooms.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $classRoom = ClassRoom::find($id);

        if ($classRoom->students()->count() > 0) {
            return redirect()->route('admin.classrooms.index')->with('error', 'Kelas tidak dapat dihapus karena masih memiliki data siswa!');
        }

        $classRoom->delete();
        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function trash()
    {
        $classRooms = ClassRoom::onlyTrashed()->get();
        return view('admin.classroom.trash', compact('classRooms'));
    }

    public function restore($id)
    {
        $classRoom = ClassRoom::onlyTrashed()->findOrFail($id);
        $classRoom->restore();

        return redirect()->route('admin.classrooms.trash')
            ->with('success', 'Data kelas berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $classRoom = ClassRoom::onlyTrashed()->findOrFail($id);
        $classRoom->forceDelete();

        return redirect()->route('admin.classrooms.trash')
            ->with('success', 'Data kelas berhasil dihapus permanen!');
    }
}
