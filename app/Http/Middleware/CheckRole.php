<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|array  ...$roles  Role atau array of roles yang diizinkan
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = Auth::user()->role;

        // Jika tidak ada role yang dispesifikasikan, lanjutkan request
        if (empty($roles)) {
            // Set view prefix berdasarkan role user
            $prefix = match ($userRole) {
                'admin'   => 'admin.',
                'teacher' => 'teacher.',
                'student' => 'student.',
                default   => abort(403, 'Role tidak valid.')
            };

            app()->instance('viewPrefix', $prefix);
            return $next($request);
        }

        // Cek apakah user memiliki salah satu role yang diizinkan
        if (in_array($userRole, $roles)) {
            // Set view prefix berdasarkan role user
            $prefix = match ($userRole) {
                'admin'   => 'admin.',
                'teacher' => 'teacher.',
                'student' => 'student.',
                default   => abort(403, 'Role tidak valid.')
            };

            app()->instance('viewPrefix', $prefix);
            return $next($request);
        }

        // Jika role tidak sesuai, redirect ke dashboard sesuai role user
        $redirectRoute = match ($userRole) {
            'admin'   => 'admin.dashboard',
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
            default   => 'home'
        };

        return redirect()->route($redirectRoute)->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
