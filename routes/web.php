<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScheduleController;

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::post('/logout', function () {
//     Auth::logout();
//     return redirect('/login')->with('success', 'Anda berhasil logout.');
// })->name('logout');

// untuk profile (hanya user yang sudah login)
Route::middleware('role')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/show', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::get('/change-password', [ProfileController::class, 'editPassword'])->name('change-password');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
});

Route::middleware('role:admin')->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::prefix('/teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('index');
        Route::get('/create', [TeacherController::class, 'create'])->name('create');
        Route::post('/store', [TeacherController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TeacherController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [TeacherController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [TeacherController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [TeacherController::class, 'trash'])->name('trash');
        Route::post('/restore/{id}', [TeacherController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [TeacherController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/create', [StudentController::class, 'create'])->name('create');
        Route::post('/store', [StudentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [StudentController::class, 'update'])->name('update');
        Route::get('/export', [StudentController::class, 'export'])->name('export');
        Route::delete('/delete/{id}', [StudentController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [StudentController::class, 'trash'])->name('trash');
        Route::post('/restore/{id}', [StudentController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [StudentController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/classrooms')->name('classrooms.')->group(function () {
        Route::get('/', [ClassRoomController::class, 'index'])->name('index');
        Route::get('/create', [ClassRoomController::class, 'create'])->name('create');
        Route::post('/store', [ClassRoomController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ClassRoomController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ClassRoomController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ClassRoomController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [ClassRoomController::class, 'show'])->name('show');
        Route::get('/trash', [ClassRoomController::class, 'trash'])->name('trash');
        Route::post('/restore/{id}', [ClassRoomController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [ClassRoomController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/subjects')->name('subjects.')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/create', [SubjectController::class, 'create'])->name('create');
        Route::post('/store', [SubjectController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SubjectController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::post('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [UserController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('create');
        Route::post('/store', [ScheduleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware('role:teacher')->prefix('/teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'teacherDashboard'])->name('dashboard');

    Route::prefix('/classrooms')->name('classrooms.')->group(function () {
        Route::get('/show/{id}', [ClassRoomController::class, 'show'])->name('show');
    });

    Route::prefix('/attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/create', [AttendanceController::class, 'create'])->name('create');
        Route::post('/bulk-store', [AttendanceController::class, 'bulkStore'])->name('bulk-store');
        Route::get('/edit/{id}', [AttendanceController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AttendanceController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AttendanceController::class, 'destroy'])->name('destroy');
        Route::get('/report', [AttendanceController::class, 'report'])->name('report');
        Route::get('/export', [AttendanceController::class, 'export'])->name('export');
    });

    Route::prefix('/schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/export-pdf', [ScheduleController::class, 'exportPdf'])->name('export-pdf');
    });
});

Route::middleware('role:student')->prefix('/student')->name('student.')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'studentDashboard'])->name('dashboard');
});

