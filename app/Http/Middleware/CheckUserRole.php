<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // Untuk periksa apakah role user ada di dalam daftar $roles yang diizinkan
        if (!in_array($user->role, $roles)) {
            // Kalau tidak diizinkan, redirect ke home atau halaman lain
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}