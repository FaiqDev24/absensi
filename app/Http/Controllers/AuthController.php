<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil Login sebagai Admin!');
            } elseif (Auth::user()->role == 'teacher') {
                return redirect()->route('teacher.dashboard')->with('success', 'Berhasil Login sebagai Teacher!');
            } elseif (Auth::user()->role == 'student') {
                return redirect()->route('student.dashboard')->with('success', 'Berhasil Login sebagai Siswa!');
            }
        }
        return redirect()->route('home')->with('error', 'Username atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home')->with('error', 'Anda telah logout! silahkan login kembali untuk akses.');
    }

    public function dashboard()
    {
        $data = [
            'teacherCount' => Teacher::count(),
            'classCount' => ClassRoom::count(),
            'studentCount' => Student::count(),
        ];

        return view('admin.dashboard', $data);
    }

    public function teacherDashboard()
    {
        $user = Auth::user();

        $teacher = Teacher::where('id_user', $user->id)->first();

        if (!$teacher) {
            return view('teacher.dashboard', [
                'error' => 'Data guru tidak ditemukan untuk akun ini.',
                'todaySchedules' => collect(),
                'classrooms' => collect(), // pastikan dikirim agar tidak error di dropdown
            ]);
        }

        $today = now()->format('l');

        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $today = $days[$today] ?? $today;

        // Ambil jadwal hari ini
        // $todaySchedules = Schedule::where('teacher_id', $teacher->id)->where('day', $today)->with(['subject', 'classroom'])->get();

        $classrooms = ClassRoom::all();

        return view('teacher.dashboard', compact('teacher', 'today', 'classrooms'));
    }

    public function studentDashboard()
    {
        $user = Auth::user();
        $student = Student::where('id_user', $user->id)->first();

        return view('student.dashboard', compact('student'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
