<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek 1: Apakah user sudah login?
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek 2: Apakah role-nya BUKAN admin?
        if (Auth::user()->role !== 'admin') {
            // Jika bukan admin, tendang keluar (Error 403 Forbidden)
            abort(403, 'AKSES DITOLAK: Halaman ini khusus Administrator.');
        }

        // Jika lolos (Admin), silakan lanjut
        return $next($request);
    }
}