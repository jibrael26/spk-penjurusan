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
        // Ambil data hasil tes terakhir milik user yang sedang login untuk ditampilkan di halaman dashboard/index
        $latestScore = AssessmentScore::where('user_id', auth()->id())->latest()->first();

        return view('assessment.index', compact('latestScore'));
    }
    
    public function store(Request $request)
{
    $request->validate([
        'answers' => 'required|array',
    ], [
        'answers.required' => 'Anda harus menjawab seluruh pertanyaan terlebih dahulu.',
    ]);

    $answers = $request->input('answers');
    
    $allCriteria = Criteria::all();
    $studentScoresRaw = [];
    $scoresPerCriteria = [];

    // Inisialisasi awal skor per kriteria (K01 - K05)
    foreach ($allCriteria as $criteria) {
        $kode = strtolower($criteria->kode_kriteria); // contoh: 'k01', 'k02', dst.
        $studentScoresRaw[$kode] = 0; 

        $scoresPerCriteria[$criteria->id] = [
            'nama_jurusan' => $criteria->nama_kriteria,
            'kode_jurusan' => $kode,
            'skor_minat'   => 0,
            'skor_bakat'   => 0,
            'total_skor'   => 0
        ];
    }
    
    // CONTOH MATRIKS BOBOT KORELASI INDIKATOR KE JURUSAN (K01 - K05)
    // Sesuaikan bobot (1 sampai 5) seberapa besar indikator cocok dengan jurusan tertentu
    $bobotIndikatorJurusan = [
        'r'  => ['k01' => 5, 'k02' => 3, 'k03' => 1, 'k04' => 5, 'k05' => 2], // Realistic
        'i'  => ['k01' => 3, 'k02' => 4, 'k03' => 4, 'k04' => 3, 'k05' => 5], // Investigative
        'a'  => ['k01' => 2, 'k02' => 2, 'k03' => 5, 'k04' => 2, 'k05' => 4], // Artistic
        's'  => ['k01' => 3, 'k02' => 3, 'k03' => 4, 'k04' => 1, 'k05' => 2], // Social
        'e'  => ['k01' => 4, 'k02' => 3, 'k03' => 5, 'k04' => 2, 'k05' => 2], // Enterprising
        'c'  => ['k01' => 2, 'k02' => 3, 'k03' => 5, 'k04' => 2, 'k05' => 3], // Conventional
        // Indikator Bakat (CHC: Gf, Gc, Gv, Gq, Gwm, Gs)
        'gf' => ['k01' => 3, 'k02' => 3, 'k03' => 5, 'k04' => 4, 'k05' => 5],
        'gc' => ['k01' => 3, 'k02' => 3, 'k03' => 4, 'k04' => 2, 'k05' => 3],
        'gv' => ['k01' => 2, 'k02' => 2, 'k03' => 3, 'k04' => 5, 'k05' => 5],
        'gq' => ['k01' => 2, 'k02' => 3, 'k03' => 5, 'k04' => 3, 'k05' => 4],
        'gwm'=> ['k01' => 3, 'k02' => 3, 'k03' => 4, 'k04' => 4, 'k05' => 5],
        'gs' => ['k01' => 3, 'k02' => 3, 'k03' => 4, 'k04' => 3, 'k05' => 4],
    ];

    $questions = Question::with('criteria')->whereIn('id', array_keys($answers))->get();
    
    foreach ($questions as $question) {
        $jawabanSiswa = (int) $answers[$question->id];
        // Ambil huruf depan indikator, misal 'R1' jadi 'r', 'Gf1' jadi 'gf'
        $rawIndikator = strtolower(preg_replace('/[0-9]+/', '', $question->kode_indikator ?? ''));
        
        // Tentukan bobot poin berdasarkan jawaban siswa
        $poinDiperoleh = 0;
        if ($question->fase == 1) {
            $poinDiperoleh = $jawabanSiswa; // Skala Likert 1-5
        } elseif ($question->fase == 2) {
            $kunciJawaban = (int) ($question->kunci_jawaban ?? 1);
            if ($jawabanSiswa === $kunciJawaban) {
                $poinDiperoleh = 5; // Bobot jika jawaban benar di pilihan ganda
            }
        }

        // Distribusikan poin ke seluruh jurusan berdasarkan matriks korelasi indikator
        if (isset($bobotIndikatorJurusan[$rawIndikator])) {
            foreach ($bobotIndikatorJurusan[$rawIndikator] as $kodeJurusan => $pengali) {
                $tambahSkor = $poinDiperoleh * $pengali;
                $studentScoresRaw[$kodeJurusan] += $tambahSkor;

                // Cari ID kriteria yang bersesuaian untuk rincian tampilan
                foreach ($allCriteria as $crit) {
                    if (strtolower($crit->kode_kriteria) === $kodeJurusan) {
                        if ($question->fase == 1) {
                            $scoresPerCriteria[$crit->id]['skor_minat'] += $tambahSkor;
                        } else {
                            $scoresPerCriteria[$crit->id]['skor_bakat'] += $tambahSkor;
                        }
                    }
                }
            }
        }
    }

    // Kalkulasi Skor Akhir & Penentuan Rekomendasi Tertinggi
    $highestDetailScore = -1;
    $recommendedJurusanId = null;

    foreach ($scoresPerCriteria as $id => $data) {
        $totalSkorJurusan = $studentScoresRaw[$data['kode_jurusan']] ?? 0;
        $scoresPerCriteria[$id]['total_skor'] = $totalSkorJurusan;

        if ($totalSkorJurusan > $highestDetailScore) {
            $highestDetailScore = $totalSkorJurusan;
            $recommendedJurusanId = $id; 
        }
    }

    // Jalankan K-Means Clustering dengan data skor yang sudah terdistribusi merata
    $recommendedCluster = $this->calculateKMeans($studentScoresRaw);

    // Simpan ke database
    AssessmentScore::updateOrCreate(
        ['user_id' => auth()->id()],
        array_merge(
            $studentScoresRaw, 
            [
                'criteria_id'         => $recommendedJurusanId, 
                'score'               => $highestDetailScore,
                'details'             => json_encode($scoresPerCriteria), 
                'recommended_cluster' => $recommendedCluster 
            ]
        )
    );

    $userId = auth()->id() ?? session()->getId();
    Session::forget('paket_soal_cbt_' . $userId);

    return redirect()->route('assessment.index')->with('success', 'Analisis Penjurusan berhasil! Rekomendasi jurusan Anda telah diperbarui.');
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
     * Menampilkan halaman lembar soal asesmen (Fase 1 & Fase 2 terpisah namun tetap acak)
     */
    public function create()
    {
        $userId = auth()->id() ?? session()->getId();
        $sessionKey = 'paket_soal_cbt_' . $userId;

        // Jika siswa belum punya paket soal aktif, buatkan paket baru secara acak per kriteria dan simpan di session
        if (!Session::has($sessionKey)) {
            $kriteriaList = Criteria::all();
            $pertanyaanTerpilih = collect();

            foreach ($kriteriaList as $kriteria) {
                // Tarik 3 soal Fase 1 (Minat) per Kriteria secara acak
                $fase1 = Question::where('criteria_id', $kriteria->id)
                               ->where('fase', 1)
                               ->inRandomOrder()->limit(4)->pluck('id');
                               
                // Tarik 2 soal Fase 2 (Bakat) per Kriteria secara acak
                $fase2 = Question::where('criteria_id', $kriteria->id)
                               ->where('fase', 2)
                               ->inRandomOrder()->limit(4)->pluck('id');
                               
                $pertanyaanTerpilih = $pertanyaanTerpilih->merge($fase1)->merge($fase2);
            }
            
            Session::put($sessionKey, $pertanyaanTerpilih->toArray());
        }

        // Ambil ID dari session
        $soalIds = Session::get($sessionKey);

        $fase1Questions = Question::whereIn('id', $soalIds)
                            ->where('fase', 1)
                            ->inRandomOrder()
                            ->get();

        $fase2Questions = Question::whereIn('id', $soalIds)
                            ->where('fase', 2)
                            ->inRandomOrder()
                            ->get();

        return view('assessment.assessment', compact('fase1Questions', 'fase2Questions'));
    }

    public function dashboard()
    {
        // Ambil data hasil tes/skor terakhir milik siswa yang sedang login
        $latestScore = AssessmentScore::where('user_id', auth()->id())->latest()->first();
        
        $details = [];
        if ($latestScore && $latestScore->details) {
            $details = json_decode($latestScore->details, true);
            // Urutkan detail dari skor tertinggi ke terendah
            usort($details, function($a, $b) {
                return $b['total_skor'] <=> $a['total_skor'];
            });
        }

        // Kirim data ke view dashboard.blade.php
        return view('dashboard', compact('latestScore', 'details'));
    }
}