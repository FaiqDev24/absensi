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

        // Filter by day
        if ($request->filled('day')) {
            $query->byDay($request->day);
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

        $schedules = $query->orderBy('day')->orderBy('start_time')->get();

        // Data untuk filter
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();
        $classRooms = ClassRoom::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view($this->getViewPrefix() . '.schedule.index', compact('schedules', 'teachers', 'subjects', 'classRooms', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::with('user')->get();
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
            'subject_id' => 'required|exists:subjects,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
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
        $teachers = Teacher::with('user')->get();
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
            'subject_id' => 'required|exists:subjects,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
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
}
