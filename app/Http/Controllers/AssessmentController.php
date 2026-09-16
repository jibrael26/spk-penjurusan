<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AssessmentController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? session()->getId(); // Fallback jika guest
        $sessionKey = 'paket_soal_cbt_' . $userId;

        // Jika siswa belum punya paket soal aktif, buatkan paket baru
        if (!Session::has($sessionKey)) {
            $kriteriaList = Criteria::all();
            $pertanyaanTerpilih = collect();

            foreach ($kriteriaList as $kriteria) {
                // Tarik 3 soal acak untuk Fase 1 (Angket) per Kriteria
                $fase1 = Question::where('criteria_id', $kriteria->id)
                                 ->where('fase', 1)
                                 ->inRandomOrder()->limit(3)->pluck('id');
                                 
                // Tarik 2 soal acak untuk Fase 2 (Pilihan) per Kriteria
                $fase2 = Question::where('criteria_id', $kriteria->id)
                                 ->where('fase', 2)
                                 ->inRandomOrder()->limit(2)->pluck('id');
                                 
                $pertanyaanTerpilih = $pertanyaanTerpilih->merge($fase1)->merge($fase2);
            }
            
            // Simpan daftar ID ke session
            Session::put($sessionKey, $pertanyaanTerpilih->toArray());
        }

        // Ambil ID dari session, lalu panggil datanya dan acak urutan tampilannya
        $soalIds = Session::get($sessionKey);
        $questions = Question::whereIn('id', $soalIds)->inRandomOrder()->get();

        return view('assessment.assessment', compact('questions'));
    }
    
    // Method store() Anda sebelumnya tidak perlu banyak berubah
    // karena value dari form sudah seragam berupa angka 1-5
}