<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    /**
     * Get view prefix based on authenticated user's role
     */
    protected function getViewPrefix()
    {
        return auth()->user()->role;
    }

    /**
     * Get redirect route name based on user's role
     */
    protected function getRedirectRoute($action)
    {
        $prefix = $this->getViewPrefix();
        return "{$prefix}.classrooms.{$action}";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classRooms = ClassRoom::all();
        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.classroom.index", compact('classRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.classroom.create");
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
            return redirect()->route($this->getRedirectRoute('index'))
                ->with('success', 'Kelas berhasil ditambahkan.');
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
            $q->join('users', 'students.id_user', '=', 'users.id')
              ->orderBy('users.name', 'asc')
              ->select('students.*');
        }])->find($id);

        $viewPrefix = $this->getViewPrefix();

        // Coba gunakan view detail jika ada, jika tidak gunakan create
        $viewPath = "{$viewPrefix}.classroom.detail";
        if (!view()->exists($viewPath)) {
            $viewPath = "{$viewPrefix}.classroom.create";
        }

        // Inisialisasi koleksi kosong untuk menampung jadwal
        $teacherSchedules = collect();
        
        // Cek jika user yang login adalah guru
        if (auth()->user()->role === 'teacher') {
            // Ambil data guru yang berasosiasi dengan user login
            $teacher = \App\Models\Teacher::where('id_user', auth()->id())->first();
            
            if ($teacher) {
                // Ambil jadwal mengajar guru DI KELAS INI (berdasarkan $id kelas)
                $teacherSchedules = \App\Models\Schedule::where('teacher_id', $teacher->id)
                    ->where('class_room_id', $id) // Filter sesuai kelas yang sedang dibuka
                    // Logika Filter Kadaluarsa:
                    ->where(function ($query) {
                        // 1. Ambil yang tanggalnya di MASA DEPAN
                        $query->whereDate('date', '>', now()->toDateString())
                            // 2. ATAU yang tanggalnya HARI INI...
                            ->orWhere(function ($q) {
                                $q->whereDate('date', '=', now()->toDateString())
                                  // ...tetapi jam selesainya belum lewat
                                  ->whereTime('end_time', '>', now()->format('H:i:s'));
                            });
                    })
                    // Eager load data mata pelajaran
                    ->with('subject')
                    // Urutkan jadwal biar rapi
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->get();
            }
        }

        // Kembalikan view dengan membawa data classRoom dan filtered schedules
        return view($viewPath, compact('classRoom', 'teacherSchedules'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $classRoom = ClassRoom::find($id);
        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.classroom.edit", compact('classRoom'));
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

        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $classRoom = ClassRoom::find($id);

        if ($classRoom->students()->count() > 0) {
            return redirect()->route($this->getRedirectRoute('index'))
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki data siswa!');
        }

        $classRoom->delete();
        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Kelas berhasil dihapus.');
    }

    public function trash()
    {
        $classRooms = ClassRoom::onlyTrashed()->get();
        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.classroom.trash", compact('classRooms'));
    }

    public function restore($id)
    {
        $classRoom = ClassRoom::onlyTrashed()->findOrFail($id);
        $classRoom->restore();

        return redirect()->route($this->getRedirectRoute('trash'))
            ->with('success', 'Data kelas berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $classRoom = ClassRoom::onlyTrashed()->findOrFail($id);
        $classRoom->forceDelete();

        return redirect()->route($this->getRedirectRoute('trash'))
            ->with('success', 'Data kelas berhasil dihapus permanen!');
    }
}

