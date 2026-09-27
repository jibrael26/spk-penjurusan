<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Criteria;
use App\Models\AssessmentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AssessmentController extends Controller
{
    /**
     * Centroid ideal untuk masing-masing Jurusan SMK (K01 - K05)
     * Diset dengan nilai dominan yang jelas agar K-Means dapat membedakan klaster dengan tajam.
     */
    private $centroids = [
        'K01 - Agribisnis Tanaman Pangan dan Hortikultura (ATPH)' => ['k01' => 45, 'k02' => 15, 'k03' => 10, 'k04' => 10, 'k05' => 10],
        'K02 - Agribisnis Pengolahan Hasil Pertanian (APHP)'     => ['k01' => 15, 'k02' => 45, 'k03' => 10, 'k04' => 10, 'k05' => 10],
        'K03 - Akuntansi dan Keuangan Lembaga (AKL)'            => ['k01' => 10, 'k02' => 10, 'k03' => 45, 'k04' => 10, 'k05' => 15],
        'K04 - Teknik Kendaraan Ringan Otomotif (TKRO)'         => ['k01' => 10, 'k02' => 10, 'k03' => 10, 'k04' => 45, 'k05' => 10],
        'K05 - Teknik Komputer dan Jaringan (TKJ)'              => ['k01' => 10, 'k02' => 10, 'k03' => 15, 'k04' => 10, 'k05' => 45],
    ];

    public function index()
    {
        $userId = auth()->id() ?? session()->getId();
        $sessionKey = 'paket_soal_cbt_' . $userId;

        if (!Session::has($sessionKey)) {
            $kriteriaList = Criteria::all();
            $pertanyaanTerpilih = collect();

            foreach ($kriteriaList as $kriteria) {
                // Tarik 3 soal Fase 1 (Minat) per Kriteria
                $fase1 = Question::where('criteria_id', $kriteria->id)
                                 ->where('fase', 1)
                                 ->inRandomOrder()->limit(3)->pluck('id');
                                 
                // Tarik 2 soal Fase 2 (Bakat) per Kriteria
                $fase2 = Question::where('criteria_id', $kriteria->id)
                                 ->where('fase', 2)
                                 ->inRandomOrder()->limit(2)->pluck('id');
                                 
                $pertanyaanTerpilih = $pertanyaanTerpilih->merge($fase1)->merge($fase2);
            }
            
            Session::put($sessionKey, $pertanyaanTerpilih->toArray());
        }

        $soalIds = Session::get($sessionKey);
        $questions = Question::whereIn('id', $soalIds)->inRandomOrder()->get();

        // Ambil data hasil tes terakhir milik user yang sedang login untuk ditampilkan di halaman
        $latestScore = AssessmentScore::where('user_id', auth()->id())->latest()->first();

        return view('assessment.index', compact('questions', 'latestScore'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
        ], [
            'answers.required' => 'Anda harus menjawab seluruh pertanyaan terlebih dahulu.',
        ]);

        $answers = $request->input('answers');
        
        $studentScores = [
            'k01' => 0, 'k02' => 0, 'k03' => 0, 'k04' => 0, 'k05' => 0
        ];
        
        $questions = Question::with('criteria')->whereIn('id', array_keys($answers))->get();
        
        foreach ($questions as $question) {
            $kodeCriteria = strtolower($question->criteria->kode_kriteria ?? '');
            
            if (array_key_exists($kodeCriteria, $studentScores)) {
                $jawabanSiswa = (int) $answers[$question->id];

                // PENILAIAN 2 FASE DENGAN PEMBOBOTAN DINAMIS
                if ($question->fase == 1) {
                    // Fase 1: Skala Likert (Minat 1-5). Diberi pengali x3 agar akumulasi poin memiliki rentang luas.
                    $studentScores[$kodeCriteria] += ($jawabanSiswa * 3);
                    
                } elseif ($question->fase == 2) {
                    // Fase 2: Pilihan Ganda (Bakat Kognitif berdasarkan Kunci Jawaban)
                    $kunciJawaban = (int) ($question->kunci_jawaban ?? 1);
                    
                    if ($jawabanSiswa === $kunciJawaban) {
                        // Jika jawaban benar, berikan bobot tinggi (misal: 15 poin)
                        $studentScores[$kodeCriteria] += 15; 
                    }
                }
            }
        }

        // Jalankan Algoritma K-Means (Euclidean Distance)
        $recommendedCluster = $this->calculateKMeans($studentScores);

        // Simpan ke database
        AssessmentScore::create(array_merge(
            ['user_id' => auth()->id()],
            $studentScores,
            ['recommended_cluster' => $recommendedCluster]
        ));

        // Bersihkan session ujian
        $userId = auth()->id() ?? session()->getId();
        Session::forget('paket_soal_cbt_' . $userId);

        return redirect()->route('assessment.index')->with('success', 'Analisis K-Means berhasil! Rekomendasi jurusan Anda telah diperbarui.');
    }

    private function calculateKMeans($studentScores)
    {
        $minDistance = INF;
        $closestCluster = null;

        foreach ($this->centroids as $clusterName => $centroidValues) {
            $distance = 0;
            
            foreach ($centroidValues as $key => $centroidScore) {
                $studentScore = $studentScores[$key] ?? 0;
                $distance += pow($studentScore - $centroidScore, 2);
            }
            
            $euclideanDistance = sqrt($distance);

            if ($euclideanDistance < $minDistance) {
                $minDistance = $euclideanDistance;
                $closestCluster = $clusterName;
            }
        }

        return $closestCluster;
    }

    /**
     * Menampilkan halaman lembar soal asesmen (Fase 1 & Fase 2)
     */
    public function create()
    {
        $userId = auth()->id() ?? session()->getId();
        $sessionKey = 'paket_soal_cbt_' . $userId;

        // Jika siswa belum punya paket soal aktif, buatkan paket baru secara acak per kriteria
        if (!\Illuminate\Support\Facades\Session::has($sessionKey)) {
            $kriteriaList = \App\Models\Criteria::all();
            $pertanyaanTerpilih = collect();

            foreach ($kriteriaList as $kriteria) {
                // Tarik 3 soal Fase 1 (Minat) per Kriteria
                $fase1 = \App\Models\Question::where('criteria_id', $kriteria->id)
                                 ->where('fase', 1)
                                 ->inRandomOrder()->limit(3)->pluck('id');
                                 
                // Tarik 2 soal Fase 2 (Bakat) per Kriteria
                $fase2 = \App\Models\Question::where('criteria_id', $kriteria->id)
                                 ->where('fase', 2)
                                 ->inRandomOrder()->limit(2)->pluck('id');
                                 
                $pertanyaanTerpilih = $pertanyaanTerpilih->merge($fase1)->merge($fase2);
            }
            
            \Illuminate\Support\Facades\Session::put($sessionKey, $pertanyaanTerpilih->toArray());
        }

        // Ambil ID dari session, lalu panggil datanya dan acak urutan tampilannya
        $soalIds = \Illuminate\Support\Facades\Session::get($sessionKey);
        $questions = \App\Models\Question::whereIn('id', $soalIds)->inRandomOrder()->get();

        return view('assessment.assessment', compact('questions'));
    }
}