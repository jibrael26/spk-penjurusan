<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
    $totalSiswa = \App\Models\User::where('is_admin', false)->count();
    $totalSudahTes = \App\Models\AssessmentScore::count();
    return view('admin.dashboard', compact('totalSiswa', 'totalSudahTes'));
}
}
