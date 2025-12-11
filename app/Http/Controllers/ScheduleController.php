<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Get view prefix based on user role
     */
    private function getViewPrefix()
    {
        return auth()->user()->role;
    }

    /**
     * Get redirect route based on user role
     */
    private function getRedirectRoute($action)
    {
        $role = auth()->user()->role;
        return "{$role}.schedules.{$action}";
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['teacher.user', 'subject', 'classRoom']);

        // Filter untuk teacher: hanya jadwal sendiri
        if (auth()->user()->role === 'teacher') {
            $teacher = Teacher::where('id_user', Auth::id())->first();
            if ($teacher) {
                $query->where('teacher_id', $teacher->id);
            }
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->byDate($request->date);
        }

        // Filter by teacher (admin only)
        if ($request->filled('teacher_id') && auth()->user()->role === 'admin') {
            $query->byTeacher($request->teacher_id);
        }

        // Filter by class
        if ($request->filled('class_room_id')) {
            $query->byClass($request->class_room_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->bySubject($request->subject_id);
        }

        // Logic untuk menyembunyikan jadwal yang "lewat" (expired)
        // date > today OR (date == today AND end_time > now)
        $today = now()->toDateString();
        $nowTime = now()->format('H:i:s');

        $query->where(function($q) use ($today, $nowTime) {
            $q->where('date', '>', $today)
              ->orWhere(function($subQ) use ($today, $nowTime) {
                  $subQ->where('date', $today)
                       ->whereTime('end_time', '>', $nowTime);
              });
        });

        $schedules = $query->orderBy('date', 'asc')
                           ->orderBy('start_time', 'asc')
                           ->get();

        // Data untuk filter
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();
        $classRooms = ClassRoom::all();

        return view($this->getViewPrefix() . '.schedule.index', compact('schedules', 'teachers', 'subjects', 'classRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::with(['user', 'subjects'])->get();
        $subjects = Subject::all();
        $classRooms = ClassRoom::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view($this->getViewPrefix() . '.schedule.create', compact('teachers', 'subjects', 'classRooms', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => [
                'required', // Wajib diisi
                'exists:subjects,id', // Harus ada di tabel subjects
                // Validasi Custom: Pastikan pelajaran yang dipilih diajarkan oleh guru tersebut
                function ($attribute, $value, $fail) use ($request) {
                    // Cari data guru berdasarkan teacher_id yang dikirim
                    $teacher = Teacher::find($request->teacher_id);
                    // Jika guru ditemukan, DAN guru tersebut TIDAK punya relasi dengan subject_id ini
                    if ($teacher && !$teacher->subjects->contains($value)) {
                        // Maka gagalkan validasi dengan pesan error
                        $fail('Mata pelajaran ini tidak diajarkan oleh guru yang dipilih.');
                    }
                },
            ],
            'class_room_id' => 'required|exists:class_rooms,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        try {
            Schedule::create($request->all());
            return redirect()->route($this->getRedirectRoute('index'))
                ->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan jadwal. Mungkin ada konflik jadwal.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $teachers = Teacher::with(['user', 'subjects'])->get();
        $subjects = Subject::all();
        $classRooms = ClassRoom::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view($this->getViewPrefix() . '.schedule.edit', compact('schedule', 'teachers', 'subjects', 'classRooms', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => [
                'required', // Wajib diisi
                'exists:subjects,id', // Harus ada di tabel subjects
                // Validasi Custom: Pastikan pelajaran yang dipilih diajarkan oleh guru tersebut
                function ($attribute, $value, $fail) use ($request) {
                    // Cari data guru berdasarkan teacher_id yang dikirim
                    $teacher = Teacher::find($request->teacher_id);
                    // Jika guru ditemukan, DAN guru tersebut TIDAK punya relasi dengan subject_id ini
                    if ($teacher && !$teacher->subjects->contains($value)) {
                        // Maka gagalkan validasi dengan pesan error
                        $fail('Mata pelajaran ini tidak diajarkan oleh guru yang dipilih.');
                    }
                },
            ],
            'class_room_id' => 'required|exists:class_rooms,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->update($request->all());

            return redirect()->route($this->getRedirectRoute('index'))
                ->with('success', 'Jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui jadwal. Mungkin ada konflik jadwal.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $query = Schedule::with(['teacher.user', 'subject', 'classRoom']);

        // Filter untuk teacher: hanya jadwal sendiri
        if (auth()->user()->role === 'teacher') {
            $teacher = Teacher::where('id_user', Auth::id())->first();
            if ($teacher) {
                $query->where('teacher_id', $teacher->id);
            }
        }

        // Apply filters consistently
        // Apply filters consistently
        if ($request->filled('date')) {
            $query->byDate($request->date);
        }
        if ($request->filled('class_room_id')) {
            $query->byClass($request->class_room_id);
        }
        if ($request->filled('subject_id')) {
            $query->bySubject($request->subject_id);
        }

        $schedules = $query->orderBy('date', 'asc')->orderBy('start_time', 'asc')->get();
        // Days array no longer needed but if view expects it, remove it from compact or pass null
        // teacher.schedule.pdf view was updated to not use it?
        // Let's check: I updated teacher.schedule.pdf previously to remove $schedule->day
        // And I don't see $days being used in my content replacement for that view earlier.
        // But to be safe, I can remove it.

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('teacher.schedule.pdf', compact('schedules'));
        return $pdf->download('jadwal_mengajar_'. date('Y-m-d') .'.pdf');
    }
}
