<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\SubjectController;

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::post('/logout', function () {
//     Auth::logout();
//     return redirect('/login')->with('success', 'Anda berhasil logout.');
// })->name('logout');

Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function () {
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
});

Route::middleware('isTeacher')->prefix('/teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'teacherDashboard'])->name('dashboard');
});
