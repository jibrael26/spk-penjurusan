<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentScore;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    // Menampilkan halaman kuesioner
    public function index()
    {
        return view('assessment.index');
    }

    // Menyimpan data kuesioner ke MySQL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'realistic' => 'required|integer|min:1|max:5',
            'investigative' => 'required|integer|min:1|max:5',
            'artistic' => 'required|integer|min:1|max:5',
            'social' => 'required|integer|min:1|max:5',
            'enterprising' => 'required|integer|min:1|max:5',
            'conventional' => 'required|integer|min:1|max:5',
            'numerical_ability' => 'required|integer|min:1|max:5',
            'verbal_reasoning' => 'required|integer|min:1|max:5',
            'mechanical_reasoning' => 'required|integer|min:1|max:5',
        ]);

        $score = AssessmentScore::create([
            'user_id' => Auth::id(),
            'realistic' => $request->realistic,
            'investigative' => $request->investigative,
            'artistic' => $request->artistic,
            'social' => $request->social,
            'enterprising' => $request->enterprising,
            'conventional' => $request->conventional,
            'numerical_ability' => $request->numerical_ability,
            'verbal_reasoning' => $request->verbal_reasoning,
            'mechanical_reasoning' => $request->mechanical_reasoning,
        ]);

        // Redirect ke halaman hasil dengan ID data yang baru disimpan
        return redirect()->route('assessment.result', $score->id)
                         ->with('success', 'Data berhasil disimpan. Memproses rekomendasi...');
    }

    // Menampilkan hasil (Temporary/Prototype sebelum K-Means)
    public function showResult($id)
    {
        $assessment = AssessmentScore::findOrFail($id);
        
        // Logika sementara untuk tampilan prototype
        $rekomendasi = "Sistem sedang memproses data menggunakan algoritma K-Means";
        
        return view('assessment.result', compact('assessment', 'rekomendasi'));
    }
}