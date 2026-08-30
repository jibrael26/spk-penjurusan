<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecekan apakah user sedang login dan kolom is_admin bernilai 1 (true)
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // Jika bukan admin, lempar kembali ke dashboard user dengan session flash message
        return redirect()->route('dashboard')->with('error', 'Akses ditolak. Halaman tersebut khusus Administrator.');
    }
}