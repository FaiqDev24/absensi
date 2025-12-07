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
                
                // Ambil kelas dari jadwal mengajar guru (unique)
                if ($teacher) {
                    $listClass = \App\Models\Schedule::where('teacher_id', $teacher->id)
                        ->with('classRoom')
                        ->get()
                        ->pluck('classRoom')
                        ->unique('id')
                        ->sortBy('name');
                } else {
                    $listClass = collect();
                }
                
                $view->with('listClass', $listClass);
            }
        });
    }
}
