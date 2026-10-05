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
        return view('assessment.index', compact('latestScore'));
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

        $fase1Answers = session('fase1_answers', []);
        $fase2Answers = $request->input('answers');
        $answers = $fase1Answers + $fase2Answers;

        $allCriteria = Criteria::all();
        $studentScoresRaw = [];
        $maxPossibleScores = []; // Tambahan: Menyimpan potensi skor maksimal
        $scoresPerCriteria = [];
        
        // Optimasi: Jadikan dictionary/hash map agar tidak perlu loop berkali-kali nanti
        $criteriaMap = []; 

        foreach ($allCriteria as $criteria) {
            $kode = strtolower($criteria->kode_kriteria);
            $studentScoresRaw[$kode] = 0;
            $maxPossibleScores[$kode] = 0; 
            
            $criteriaMap[$kode] = $criteria->id; // Mapping ID
            
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
            $jawabanSiswa = (int) $answers[$question->id];
            $rawIndikator = strtolower(preg_replace('/[0-9]+/', '', $question->kode_indikator ?? ''));
            
            $poinDasar = 0;
            $maxPoinSoal = 5; // Asumsi skala likert max 5, dan benar max 5

            if ($question->fase == 1) {
                $poinDasar = $jawabanSiswa;
            } elseif ($question->fase == 2) {
                $kunciJawaban = (int) ($question->kunci_jawaban ?? 1);
                if ($jawabanSiswa === $kunciJawaban) {
                    $poinDasar = 5;
                }
            }

            if (isset($matrixKorelasi[$rawIndikator])) {
                foreach ($matrixKorelasi[$rawIndikator] as $kodeJurusan => $bobotPengali) {
                    // Skor aktual siswa
                    $tambahSkor = $poinDasar * $bobotPengali;
                    $studentScoresRaw[$kodeJurusan] += $tambahSkor;

                    // Skor maksimal jika siswa menjawab sempurna (untuk normalisasi)
                    $maxPossibleScores[$kodeJurusan] += ($maxPoinSoal * $bobotPengali);

                    // Map ke kriteria tanpa nested loop (Lebih efisien)
                    if (isset($criteriaMap[$kodeJurusan])) {
                        $critId = $criteriaMap[$kodeJurusan];
                        if ($question->fase == 1) {
                            $scoresPerCriteria[$critId]['skor_minat'] += $tambahSkor;
                        } else {
                            $scoresPerCriteria[$critId]['skor_bakat'] += $tambahSkor;
                        }
                    }
                }
            }
        }

        // ==========================================
        // PROSES NORMALISASI SKOR KE SKALA 0-100
        // ==========================================
        $normalizedScores = [];
        $highestDetailScore = -1;
        $recommendedJurusanId = null;

        foreach ($scoresPerCriteria as $id => $data) {
            $kode = $data['kode_jurusan'];
            $rawScore = $studentScoresRaw[$kode] ?? 0;
            $maxScore = $maxPossibleScores[$kode] > 0 ? $maxPossibleScores[$kode] : 1; // Cegah division by zero
            
            // Hitung persentase kecocokan (Skala 100)
            $persentase = round(($rawScore / $maxScore) * 100, 2);
            $normalizedScores[$kode] = $persentase;

            // Update skor di details dengan persentase (bukan raw score yang bias)
            $scoresPerCriteria[$id]['total_skor'] = $persentase;

            if ($persentase > $highestDetailScore) {
                $highestDetailScore = $persentase;
                $recommendedJurusanId = $id; 
            }
        }

        // Hitung K-Means menggunakan data NORMALISASI, bukan raw score!
        $recommendedCluster = $this->calculateKMeans($normalizedScores);

        AssessmentScore::updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge(
                $normalizedScores, // Simpan persentase ke database, bukan raw bias
                [
                    'criteria_id'         => $recommendedJurusanId, 
                    'score'               => $highestDetailScore,
                    'details'             => json_encode($scoresPerCriteria), 
                    'recommended_cluster' => $recommendedCluster 
                ]
            )
        );

        Session::forget('fase1_answers');

        return redirect()->route('dashboard')->with('success', 'Analisis Penjurusan berhasil! Rekomendasi jurusan Anda telah diperbarui.');
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
        
        $details = [];
        if ($latestScore && $latestScore->details) {
            $details = json_decode($latestScore->details, true);
            usort($details, function($a, $b) {
                return $b['total_skor'] <=> $a['total_skor'];
            });
        }

        return view('dashboard', compact('details', 'latestScore'));
    }
}