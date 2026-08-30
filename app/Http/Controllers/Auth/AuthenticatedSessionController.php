<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Proses validasi dan otentikasi bawaan Laravel
        $request->authenticate();

        // 2. Regenerasi session untuk mencegah serangan session fixation
        $request->session()->regenerate();

        // 3. LOGIKA BARU: Pengecekan Role Pengguna
        // Cek apakah user yang login memiliki hak akses admin (is_admin == 1)
        if ($request->user()->is_admin == 1) {
            // Arahkan ke panel khusus admin
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // Jika bukan admin (is_admin == 0 / null), arahkan ke panel siswa/user biasa
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Setelah logout, kembalikan user ke Landing Page
        return redirect('/');
    }
}