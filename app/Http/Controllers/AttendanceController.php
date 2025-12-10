<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
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
        return "{$prefix}.attendance.{$action}";
    }

    /**
     * Display a listing of attendances
     */
    public function index(Request $request)
    {
        $viewPrefix = $this->getViewPrefix();
        
        $query = Attendance::with(['student.user', 'subject', 'classRoom']);
        
        // Filter by date if provided
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }
        
        // Filter by class if provided
        if ($request->has('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }
        
        // Filter by subject if provided
        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        $attendances = $query->orderBy('date', 'desc')->paginate(20);
        $classRooms = ClassRoom::all();
        $subjects = Subject::all();
        
        return view("{$viewPrefix}.attendance.index", compact('attendances', 'classRooms', 'subjects'));
    }

    /**
     * Show the form for creating new attendance
     */
    public function create(Request $request)
    {
        $viewPrefix = $this->getViewPrefix();
        
        // Get teacher's subject
        $teacher = Teacher::where('id_user', Auth::id())->first();
        $subject = $teacher ? $teacher->subjects : null;
        
        $classRooms = ClassRoom::with('students.user')->get();
        $subjects = Subject::all();
        
        // If class and subject selected, get students
        $students = null;
        $existingAttendances = [];
        
        if ($request->has('class_room_id') && $request->has('subject_id')) {
            $classRoom = ClassRoom::with('students.user')->find($request->class_room_id);
            $students = $classRoom ? $classRoom->students : collect();
            
            // Check if attendance already exists for this date
            $date = $request->date ?? now()->format('Y-m-d');
            $existingAttendances = Attendance::where('class_room_id', $request->class_room_id)
                ->where('subject_id', $request->subject_id)
                ->whereDate('date', $date)
                ->pluck('student_id')
                ->toArray();
        }
        
        return view("{$viewPrefix}.attendance.create", compact('classRooms', 'subjects', 'students', 'subject', 'existingAttendances'));
    }

    /**
     * Store bulk attendance records
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:hadir,sakit,izin,alpha',
        ]);

        $teacher = Teacher::where('id_user', Auth::id())->first();
        
        // Validasi: Guru hanya bisa input untuk mata pelajaran yang diajarkan
        if ($teacher) {
            $teacherSubjectIds = $teacher->subjects()->pluck('subjects.id')->toArray();
            if (!in_array($request->subject_id, $teacherSubjectIds)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mata pelajaran ini.');
            }
        }
        
        foreach ($request->attendances as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'subject_id' => $request->subject_id,
                    'date' => $request->date,
                ],
                [
                    'class_room_id' => $request->class_room_id,
                    'teacher_id' => $teacher ? $teacher->id : null,
                    'status' => $attendanceData['status'],
                    'notes' => $attendanceData['notes'] ?? null,
                ]
            );
        }

        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Kehadiran berhasil disimpan.');
    }

    /**
     * Show the form for editing attendance
     */
    public function edit($id)
    {
        $attendance = Attendance::with(['student.user', 'classRoom', 'subject'])->findOrFail($id);
        $viewPrefix = $this->getViewPrefix();
        
        return view("{$viewPrefix}.attendance.edit", compact('attendance'));
    }

    /**
     * Update the specified attendance
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:hadir,sakit,izin,alpha',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->only(['status', 'notes']));

        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Kehadiran berhasil diperbarui.');
    }

    /**
     * Remove the specified attendance
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Kehadiran berhasil dihapus.');
    }

    /**
     * Show attendance report
     */
    public function report(Request $request)
    {
        $viewPrefix = $this->getViewPrefix();
        
        $classRooms = ClassRoom::all();
        $subjects = Subject::all();
        
        $students = null;
        $reportData = null;
        
        if ($request->has('class_room_id') && $request->has('month') && $request->has('year')) {
            $classRoom = ClassRoom::with('students.user')->find($request->class_room_id);
            $students = $classRoom ? $classRoom->students : collect();
            
            $reportData = [];
            foreach ($students as $student) {
                $attendances = Attendance::where('student_id', $student->id)
                    ->byMonth($request->month, $request->year);
                
                if ($request->has('subject_id')) {
                    $attendances->where('subject_id', $request->subject_id);
                }
                
                $reportData[$student->id] = [
                    'student' => $student,
                    'hadir' => (clone $attendances)->hadir()->count(),
                    'sakit' => (clone $attendances)->sakit()->count(),
                    'izin' => (clone $attendances)->izin()->count(),
                    'alpha' => (clone $attendances)->alpha()->count(),
                    'total' => $attendances->count(),
                ];
            }
        }
        
        return view("{$viewPrefix}.attendance.report", compact('classRooms', 'subjects', 'reportData'));
    }

    /**
     * Export attendance report to Excel
     */
    public function export(Request $request)
    {
        // TODO: Implement Excel export using maatwebsite/excel
        // For now, return CSV
        
        $classRoom = ClassRoom::with('students.user')->find($request->class_room_id);
        $students = $classRoom ? $classRoom->students : collect();
        
        $filename = "attendance_report_{$request->month}_{$request->year}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($students, $request) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['NIS', 'Nama', 'Hadir', 'Sakit', 'Izin', 'Alpha', 'Total']);
            
            // Data
            foreach ($students as $student) {
                $attendances = Attendance::where('student_id', $student->id)
                    ->byMonth($request->month, $request->year);
                
                if ($request->has('subject_id')) {
                    $attendances->where('subject_id', $request->subject_id);
                }
                
                fputcsv($file, [
                    $student->nis ?? '-',
                    $student->user->name ?? $student->name,
                    (clone $attendances)->hadir()->count(),
                    (clone $attendances)->sakit()->count(),
                    (clone $attendances)->izin()->count(),
                    (clone $attendances)->alpha()->count(),
                    $attendances->count(),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
