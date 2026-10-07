<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Criteria;
use App\Models\AssessmentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AssessmentController extends Controller
{
    private $centroids = [
        'K01 - Agribisnis Tanaman Pangan dan Hortikultura (ATPH)' => ['k01' => 55, 'k02' => 30, 'k03' => 20, 'k04' => 35, 'k05' => 20],
        'K02 - Agribisnis Pengolahan Hasil Pertanian (APHP)'     => ['k01' => 30, 'k02' => 55, 'k03' => 25, 'k04' => 20, 'k05' => 20],
        'K03 - Akuntansi dan Keuangan Lembaga (AKL)'             => ['k01' => 20, 'k02' => 25, 'k03' => 55, 'k04' => 20, 'k05' => 35],
        'K04 - Teknik Kendaraan Ringan Otomotif (TKRO)'         => ['k01' => 35, 'k02' => 20, 'k03' => 20, 'k04' => 55, 'k05' => 25],
        'K05 - Teknik Komputer dan Jaringan (TKJ)'               => ['k01' => 20, 'k02' => 20, 'k03' => 35, 'k04' => 25, 'k05' => 55],
    ];

    public function index()
    {
        $latestScore = AssessmentScore::where('user_id', auth()->id())->latest()->first();
        $riasecInsight = $this->getRiasecInsight($latestScore);

        return view('assessment.index', compact('latestScore', 'riasecInsight'));
    }

    // ================= FASE 1 =================
    public function createFase1()
    {
        $questions = Question::where('fase', 1)->get();
        return view('assessment.fase1', compact('questions'));
    }

    public function storeFase1(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
        ], [
            'answers.required' => 'Silakan jawab seluruh pertanyaan minat terlebih dahulu.',
        ]);

        // Simpan jawaban Fase 1 ke session sementara
        session(['fase1_answers' => $request->input('answers')]);

        return redirect()->route('assessment.fase2');
    }

    // ================= FASE 2 =================
    public function createFase2()
    {
        // Validasi: pastikan siswa sudah melewati Fase 1
        if (!session()->has('fase1_answers')) {
            return redirect()->route('assessment.fase1')->with('error', 'Selesaikan Fase 1 terlebih dahulu.');
        }

        $questions = Question::with('criteria')->where('fase', 2)->get();
        return view('assessment.fase2', compact('questions'));
    }

   public function storeFase2(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
        ], [
            'answers.required' => 'Silakan jawab seluruh pertanyaan bakat terlebih dahulu.',
        ]);

        // Simpan jawaban Fase 2 ke session, kalkulasi dipindah ke Fase 3
        session(['fase2_answers' => $request->input('answers')]);

        return redirect()->route('assessment.fase3'); // Menggunakan nama route yang baru
    }

    // ================= FASE 3 (BARU) =================
    public function fase3()
    {
        // Pastikan Fase 1 dan 2 sudah selesai
        if (!session()->has('fase1_answers') || !session()->has('fase2_answers')) {
            return redirect()->route('assessment.fase1')->with('error', 'Selesaikan Fase sebelumnya terlebih dahulu.');
        }

        $questions = Question::with('criteria')->where('fase', 3)->get();
        
        // Logika Waktu: 1 soal = 1 menit
        $jumlahSoal = $questions->count();
        $timeLimitInSeconds = $jumlahSoal * 60; 

        return view('assessment.fase3', compact('questions', 'timeLimitInSeconds'));
    }

    public function storefase3(Request $request)
    {
        // Validasi opsional, tergantung jika user men-submit form kosong ketika waktu habis
        $request->validate([
             'jawaban' => 'nullable|array', // Bisa nullable jika waktu habis dan ada soal kosong
        ]);

        // Ambil semua jawaban
        $fase1Answers = session('fase1_answers', []);
        $fase2Answers = session('fase2_answers', []);
        $fase3Answers = $request->input('jawaban') ?? []; // Menggunakan input 'jawaban' sesuai view form
        
        // Gabungkan seluruh fase
        $answers = $fase1Answers + $fase2Answers + $fase3Answers;

        $riasecScores = [];
        $riasecMaxScores = [];
        foreach (['r', 'i', 'a', 's', 'e', 'c'] as $dimensi) {
            $riasecScores[$dimensi] = 0;
            $riasecMaxScores[$dimensi] = 0;
        }

        $riasecQuestions = Question::where('fase', 1)->get(['id', 'kode_indikator']);
        foreach ($riasecQuestions as $question) {
            $dimensi = strtolower(substr($question->kode_indikator ?? '', 0, 1));
            if (!array_key_exists($dimensi, $riasecScores)) {
                continue;
            }

            $riasecMaxScores[$dimensi] += 5;
            $riasecScores[$dimensi] += min(5, max(0, (int) ($fase1Answers[$question->id] ?? 0)));
        }

        foreach ($riasecScores as $dimensi => $score) {
            $maxScore = $riasecMaxScores[$dimensi];
            $riasecScores[$dimensi] = $maxScore > 0
                ? round(($score / $maxScore) * 100, 2)
                : 0;
        }

        $allCriteria = Criteria::all();
        $studentScoresRaw = [];
        $maxPossibleScores = []; 
        $scoresPerCriteria = [];
        $criteriaMap = []; 

        foreach ($allCriteria as $criteria) {
            $kode = strtolower($criteria->kode_kriteria);
            $studentScoresRaw[$kode] = 0;
            $maxPossibleScores[$kode] = 0; 
            
            $criteriaMap[$kode] = $criteria->id; 
            
            $scoresPerCriteria[$criteria->id] = [
                'nama_jurusan' => $criteria->nama_kriteria,
                'kode_jurusan' => $kode,
                'skor_minat'   => 0,
                'skor_bakat'   => 0,
                'total_skor'   => 0
            ];
        }
        
        $matrixKorelasi = [
            'r'  => ['k01' => 4, 'k02' => 2, 'k03' => 1, 'k04' => 5, 'k05' => 2],
            'i'  => ['k01' => 2, 'k02' => 3, 'k03' => 4, 'k04' => 3, 'k05' => 5],
            'a'  => ['k01' => 1, 'k02' => 2, 'k03' => 5, 'k04' => 1, 'k05' => 4],
            's'  => ['k01' => 3, 'k02' => 3, 'k03' => 4, 'k04' => 2, 'k05' => 3],
            'e'  => ['k01' => 4, 'k02' => 3, 'k03' => 5, 'k04' => 2, 'k05' => 2],
            'c'  => ['k01' => 2, 'k02' => 3, 'k03' => 5, 'k04' => 2, 'k05' => 3],
            'gf' => ['k01' => 3, 'k02' => 3, 'k03' => 5, 'k04' => 4, 'k05' => 5],
            'gc' => ['k01' => 3, 'k02' => 3, 'k03' => 4, 'k04' => 2, 'k05' => 3],
            'gv' => ['k01' => 2, 'k02' => 2, 'k03' => 3, 'k04' => 5, 'k05' => 5],
            'gq' => ['k01' => 2, 'k02' => 3, 'k03' => 5, 'k04' => 3, 'k05' => 4],
            'gwm'=> ['k01' => 3, 'k02' => 3, 'k03' => 4, 'k04' => 4, 'k05' => 5],
            'gs' => ['k01' => 3, 'k02' => 3, 'k03' => 4, 'k04' => 3, 'k05' => 4],
        ];

        $questions = Question::with('criteria')->whereIn('id', array_keys($answers))->get();
        
        foreach ($questions as $question) {
            $jawabanSiswa = $answers[$question->id];
            $rawIndikator = strtolower(preg_replace('/[0-9]+/', '', $question->kode_indikator ?? ''));
            
            $poinDasar = 0;
            $maxPoinSoal = 5; 

            if ($question->fase == 1) {
                // Konversi eksplisit agar string diabaikan
                $poinDasar = (int) $jawabanSiswa;
            } elseif ($question->fase == 2 || $question->fase == 3) { // Tambahkan kondisi Fase 3 jika logikanya sama
                $kunciJawaban = (int) ($question->kunci_jawaban ?? 1);
                // Karena jawaban Fase 3 mungkin huruf "A" atau "B", sesuaikan validasinya
                if ($jawabanSiswa === $kunciJawaban || $jawabanSiswa === $question->kunci_jawaban) {
                    $poinDasar = 5;
                }
            }

            if (isset($matrixKorelasi[$rawIndikator])) {
                foreach ($matrixKorelasi[$rawIndikator] as $kodeJurusan => $bobotPengali) {
                    $tambahSkor = $poinDasar * $bobotPengali;
                    $studentScoresRaw[$kodeJurusan] += $tambahSkor;
                    $maxPossibleScores[$kodeJurusan] += ($maxPoinSoal * $bobotPengali);

                    if (isset($criteriaMap[$kodeJurusan])) {
                        $critId = $criteriaMap[$kodeJurusan];
                        if ($question->fase == 1) {
                            $scoresPerCriteria[$critId]['skor_minat'] += $tambahSkor;
                        } else {
                            // Fase 2 dan 3 masuk ke skor bakat/pengetahuan
                            $scoresPerCriteria[$critId]['skor_bakat'] += $tambahSkor;
                        }
                    }
                }
            }
        }

        // ==========================================
        // PROSES NORMALISASI SKOR
        // ==========================================
        $normalizedScores = [];
        $highestDetailScore = -1;
        $recommendedJurusanId = null;

        foreach ($scoresPerCriteria as $id => $data) {
            $kode = $data['kode_jurusan'];
            $rawScore = $studentScoresRaw[$kode] ?? 0;
            $maxScore = $maxPossibleScores[$kode] > 0 ? $maxPossibleScores[$kode] : 1; 
            
            $persentase = round(($rawScore / $maxScore) * 100, 2);
            $normalizedScores[$kode] = $persentase;
            $scoresPerCriteria[$id]['total_skor'] = $persentase;

            if ($persentase > $highestDetailScore) {
                $highestDetailScore = $persentase;
                $recommendedJurusanId = $id; 
            }
        }

        // K-Means
        $recommendedCluster = $this->calculateKMeans($normalizedScores);

        AssessmentScore::updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge(
                $normalizedScores, 
                [
                    'criteria_id'         => $recommendedJurusanId, 
                    'score'               => $highestDetailScore,
                    'details'             => json_encode($scoresPerCriteria), 
                    'riasec_scores'       => $riasecScores,
                    'recommended_cluster' => $recommendedCluster 
                ]
            )
        );

        // Bersihkan session
        Session::forget('fase1_answers');
        Session::forget('fase2_answers');

        return redirect()->route('dashboard')->with('success', 'Analisis Penjurusan Fase 3 berhasil! Rekomendasi jurusan Anda telah diperbarui.');
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

    public function dashboard()
    {
        $latestScore = AssessmentScore::where('user_id', auth()->id())->latest()->first();
        $riasecInsight = $this->getRiasecInsight($latestScore);

        return view('dashboard', compact('latestScore', 'riasecInsight'));
    }

    private function getRiasecInsight(?AssessmentScore $assessmentScore): ?array
    {
        if (!$assessmentScore || !is_array($assessmentScore->riasec_scores)) {
            return null;
        }

        $riasecProfiles = [
            'r' => [
                'label' => 'Realistic',
                'description' => 'Anda lebih menonjol pada kemampuan Realistic. Anda cenderung menyukai kegiatan praktik, penggunaan alat atau mesin, serta pekerjaan yang menghasilkan sesuatu secara nyata.',
            ],
            'i' => [
                'label' => 'Investigative',
                'description' => 'Anda lebih menonjol pada kemampuan Investigative. Anda cenderung tertarik menganalisis masalah, menggunakan logika, melakukan pengamatan, dan mencari solusi berdasarkan fakta.',
            ],
            'a' => [
                'label' => 'Artistic',
                'description' => 'Anda lebih menonjol pada kemampuan Artistic. Anda cenderung memiliki imajinasi, menyukai kebebasan berekspresi, serta tertarik menciptakan ide atau karya yang unik.',
            ],
            's' => [
                'label' => 'Social',
                'description' => 'Anda lebih menonjol pada kemampuan Social. Anda cenderung senang berkomunikasi, membantu orang lain, bekerja sama, dan berbagi pengetahuan.',
            ],
            'e' => [
                'label' => 'Enterprising',
                'description' => 'Anda lebih menonjol pada kemampuan Enterprising. Anda cenderung percaya diri dalam menyampaikan ide, memimpin, mengambil keputusan, dan mengelola kegiatan.',
            ],
            'c' => [
                'label' => 'Conventional',
                'description' => 'Anda lebih menonjol pada kemampuan Conventional. Anda cenderung teliti, teratur, menyukai data atau angka, serta nyaman bekerja dengan prosedur yang jelas.',
            ],
        ];

        $topDimension = collect($assessmentScore->riasec_scores)
            ->only(array_keys($riasecProfiles))
            ->sortDesc()
            ->keys()
            ->first();

        return $topDimension
            ? array_merge($riasecProfiles[$topDimension], [
                'score' => $assessmentScore->riasec_scores[$topDimension],
            ])
            : null;
    }
}