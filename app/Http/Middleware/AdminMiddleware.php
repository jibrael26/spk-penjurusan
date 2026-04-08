<?php

namespace App\Http\Middleware; // Pastikan namespace ini benar

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Logika pengecekan is_admin sesuai tabel MySQL [cite: 314]
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Akses ditolak.');
    }
}
