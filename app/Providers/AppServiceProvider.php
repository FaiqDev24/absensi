<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ClassRoom;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer untuk share data listClass ke navbar
        View::composer('*', function ($view) {
            // Hanya share listClass jika user adalah teacher
            if (auth()->check() && auth()->user()->role === 'teacher') {
                $teacher = \App\Models\Teacher::where('id_user', auth()->id())->first();

                // Ambil kelas dari jadwal mengajar guru (unique) yang belum kadaluarsa
                if ($teacher) {
                    // Mulai query ke tabel Schedule (Jadwal)
                    $listClass = \App\Models\Schedule::where('teacher_id', $teacher->id) // Filter: Hanya milik guru ini
                        // Grup Kondisi "ATAU" (AND ( ... OR ...)) untuk filter waktu
                        ->where(function ($query) {
                            // Opsi 1: Jadwal di masa depan (Tanggal > Hari Ini)
                            $query->whereDate('date', '>', now()->toDateString())
                                // Opsi 2: Jadwal HARI INI, tapi jam selesainya belum lewat
                                ->orWhere(function ($q) {
                                    $q->whereDate('date', '=', now()->toDateString()) // Tanggal = Hari Ini
                                      ->whereTime('end_time', '>', now()->format('H:i:s')); // Jam Selesai > Jam Sekarang
                                });
                        })
                        ->with('classRoom') // Eager Load relasi classRoom
                        ->get()             // Eksekusi Query
                        ->pluck('classRoom') // Ambil hanya kolom/objek classRoom
                        ->unique('id')      // Hapus duplikat ID Kelas
                        ->sortBy('name');   // Urutkan berdasarkan Nama Kelas
                } else {
                    $listClass = collect();
                }

                $view->with('listClass', $listClass);
            }
        });
    }
}
