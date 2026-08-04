<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AssessmentScore;
use App\Models\Question; // Import model Question ditambahkan di sini

class AdminController extends Controller
{
    public function index() {
        $totalSiswa = User::where('is_admin', false)->count();
        $totalSudahTes = AssessmentScore::count();
        return view('admin.dashboard', compact('totalSiswa', 'totalSudahTes'));
    }

    // Metode untuk manajemen data siswa
    public function dataSiswa() {
        $users = User::where('is_admin', false)->paginate(10); 
        return view('admin.users.index', compact('users'));
    }

    // Menampilkan halaman form tambah pertanyaan beserta daftar pertanyaan
    public function createQuestion()
    {
        // Ambil data pertanyaan dari database, urutkan dari terbaru, dan batasi 10 data per halaman
        $questions = Question::latest()->paginate(10);
        
        // Tampilkan view 'admin.questions' dan kirimkan variabel $questions
        return view('admin.questions', compact('questions'));
    }

    // Memproses dan menyimpan pertanyaan ke database
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'kategori'      => 'required|string',
        ]);

        Question::create($request->all());

        return redirect()->back()->with('success', 'Pertanyaan berhasil ditambahkan!');
    }
}
