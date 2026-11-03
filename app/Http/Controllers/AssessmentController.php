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
     * Profil target jurusan berdasarkan kecenderungan minat RIASEC dan
     * kemampuan CHC yang paling relevan dengan kompetensi kejuruan.
     * Nilai setiap kelompok berjumlah 100 agar mudah dikalibrasi.
     */
    private $majorProfiles = [
        'k01' => [
            'name' => 'K01 - Agribisnis Tanaman Pangan dan Hortikultura (ATPH)',
            'riasec' => ['r' => 30, 'i' => 20, 'a' => 5, 's' => 15, 'e' => 15, 'c' => 15],
            'chc' => ['gf' => 15, 'gc' => 10, 'gv' => 20, 'gq' => 10, 'gwm' => 15, 'gs' => 30],
        ],
        'k02' => [
            'name' => 'K02 - Agribisnis Pengolahan Hasil Pertanian (APHP)',
            'riasec' => ['r' => 20, 'i' => 25, 'a' => 20, 's' => 10, 'e' => 15, 'c' => 10],
            'chc' => ['gf' => 20, 'gc' => 20, 'gv' => 15, 'gq' => 15, 'gwm' => 15, 'gs' => 15],
        ],
        'k03' => [
            'name' => 'K03 - Akuntansi dan Keuangan Lembaga (AKL)',
            'riasec' => ['r' => 5, 'i' => 20, 'a' => 5, 's' => 10, 'e' => 15, 'c' => 45],
            'chc' => ['gf' => 20, 'gc' => 20, 'gv' => 5, 'gq' => 35, 'gwm' => 15, 'gs' => 5],
        ],
        'k04' => [
            'name' => 'K04 - Teknik Kendaraan Ringan Otomotif (TKRO)',
            'riasec' => ['r' => 40, 'i' => 20, 'a' => 5, 's' => 10, 'e' => 10, 'c' => 15],
            'chc' => ['gf' => 20, 'gc' => 10, 'gv' => 25, 'gq' => 10, 'gwm' => 15, 'gs' => 20],
        ],
        'k05' => [
            'name' => 'K05 - Teknik Komputer dan Jaringan (TKJ)',
            'riasec' => ['r' => 15, 'i' => 35, 'a' => 10, 's' => 5, 'e' => 10, 'c' => 25],
            'chc' => ['gf' => 25, 'gc' => 15, 'gv' => 20, 'gq' => 20, 'gwm' => 15, 'gs' => 5],
        ],
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
        $questions = $this->getAssessmentQuestions(1);
        return view('assessment.fase1', compact('questions'));
    }

    public function storeFase1(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
        ], [
            'answers.required' => 'Silakan jawab seluruh pertanyaan minat terlebih dahulu.',
        ]);

        $this->resetQuestionOrder();

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

        $questions = $this->getAssessmentQuestions(2, true);
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

        $questions = $this->getAssessmentQuestions(3, true);

        // Logika Waktu: 1 soal = 1 menit
        $jumlahSoal = $questions->count();
        $timeLimitInSeconds = $jumlahSoal * 60;

        return view('assessment.fase3', compact('questions', 'timeLimitInSeconds'));
    }

    public function storeFase3(Request $request)
    {
        $request->validate([
             'jawaban' => 'nullable|array',
        ]);

        session(['fase3_answers' => $request->input('jawaban') ?? []]);

        return redirect()->route('assessment.fase4');
    }

    public function createFase4()
    {
        if (!session()->has('fase1_answers') || !session()->has('fase2_answers') || !session()->has('fase3_answers')) {
            return redirect()->route('assessment.fase1')->with('error', 'Selesaikan Fase 1 sampai 3 terlebih dahulu.');
        }

        $questions = $this->getAssessmentQuestions(4, true);
        $answers = session('fase4_answers', []);
        $currentQuestion = $questions->first(function ($question) use ($answers) {
            return !array_key_exists($question->id, $answers);
        });
        $answeredQuestions = $questions->filter(function ($question) use ($answers) {
            return array_key_exists($question->id, $answers);
        })->values();
        $showPhase4Warning = !session()->has('fase4_warning_seen');

        return view('assessment.fase4', compact('currentQuestion', 'answeredQuestions', 'answers', 'showPhase4Warning'));
    }

    public function storeFase4(Request $request)
    {
        $questions = $this->getAssessmentQuestions(4);
        $answers = session('fase4_answers', []);

        if ($request->input('action') === 'submit') {
            $questionIds = $questions->pluck('id')->map(fn ($id) => (string) $id)->all();
            $answeredIds = array_map('strval', array_keys($answers));

            if (count(array_diff($questionIds, $answeredIds)) > 0) {
                return back()->withErrors(['jawaban' => 'Jawab seluruh pertanyaan Fase 4 terlebih dahulu.']);
            }

            $this->finalizeAssessment();

            return redirect()->route('dashboard')->with('success', 'Analisis Penjurusan selesai! Rekomendasi jurusan Anda telah diperbarui.');
        }

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|string|max:10',
        ]);

        $currentQuestion = $questions->first(function ($question) use ($answers) {
            return !array_key_exists($question->id, $answers);
        });

        if (!$currentQuestion) {
            return redirect()->route('assessment.fase4');
        }

        $submittedAnswers = $request->input('jawaban');
        $answer = $submittedAnswers[$currentQuestion->id] ?? null;
        if ($answer === null) {
            return back()->withErrors(['jawaban' => 'Jawab pertanyaan yang sedang tampil terlebih dahulu.']);
        }

        $answers[$currentQuestion->id] = $answer;
        session([
            'fase4_answers' => $answers,
            'fase4_warning_seen' => true,
        ]);

        return redirect()->route('assessment.fase4');
    }

    private function finalizeAssessment()
    {
        $fase1Answers = session('fase1_answers', []);
        $fase2Answers = session('fase2_answers', []);
        $fase3Answers = session('fase3_answers', []);
        $fase4Answers = session('fase4_answers', []);

        $answers = $fase1Answers + $fase2Answers + $fase3Answers + $fase4Answers;

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

        $chcIndicators = ['gf', 'gc', 'gv', 'gq', 'gwm', 'gs'];
        $chcCorrectAnswers = array_fill_keys($chcIndicators, 0);
        $chcQuestionCounts = array_fill_keys($chcIndicators, 0);

        foreach (Question::whereIn('fase', [2, 3, 4])->get(['kode_indikator']) as $question) {
            $indicator = strtolower(preg_replace('/[0-9]+/', '', $question->kode_indikator ?? ''));
            if (array_key_exists($indicator, $chcQuestionCounts)) {
                $chcQuestionCounts[$indicator]++;
            }
        }

        $questions = Question::with('criteria')->whereIn('id', array_keys($answers))->get();

        foreach ($questions as $question) {
            $jawabanSiswa = $answers[$question->id];
            $rawIndikator = strtolower(preg_replace('/[0-9]+/', '', $question->kode_indikator ?? ''));

            $poinDasar = 0;

            if ($question->fase == 1) {
                $poinDasar = (int) $jawabanSiswa;
            } elseif (in_array($question->fase, [2, 3, 4], true)) {
                $kunciJawaban = (int) ($question->kunci_jawaban ?? 1);
                if ($jawabanSiswa === $kunciJawaban || $jawabanSiswa === $question->kunci_jawaban) {
                    $poinDasar = 5;
                }

                if (array_key_exists($rawIndikator, $chcCorrectAnswers) && $poinDasar > 0) {
                    $chcCorrectAnswers[$rawIndikator]++;
                }
            }

        }

        $chcScores = [];
        foreach ($chcIndicators as $indicator) {
            $chcScores[$indicator] = $chcQuestionCounts[$indicator] > 0
                ? round(($chcCorrectAnswers[$indicator] / $chcQuestionCounts[$indicator]) * 100, 2)
                : 0;
        }

        $normalizedScores = [];
        $scoresPerCriteria = [];
        $highestDetailScore = -1;
        $recommendedKode = null;
        $criteriaMap = Criteria::pluck('id', 'kode_kriteria')
            ->mapWithKeys(fn ($id, $kode) => [strtolower($kode) => $id]);

        foreach ($this->majorProfiles as $kode => $profile) {
            $minatScore = $this->calculateProfileMatch($riasecScores, $profile['riasec']);
            $bakatScore = $this->calculateProfileMatch($chcScores, $profile['chc']);
            $totalScore = round(($minatScore * 0.45) + ($bakatScore * 0.55), 2);

            $normalizedScores[$kode] = $totalScore;
            $scoresPerCriteria[$kode] = [
                'nama_jurusan' => $profile['name'],
                'kode_jurusan' => $kode,
                'skor_minat' => $minatScore,
                'skor_bakat' => $bakatScore,
                'total_skor' => $totalScore,
            ];

            if ($totalScore > $highestDetailScore) {
                $highestDetailScore = $totalScore;
                $recommendedKode = $kode;
            }
        }

        $recommendedJurusanId = $criteriaMap[$recommendedKode] ?? null;
        $recommendedCluster = $this->majorProfiles[$recommendedKode]['name'] ?? null;

        AssessmentScore::updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge(
                $normalizedScores,
                [
                    'criteria_id'         => $recommendedJurusanId,
                    'score'               => $highestDetailScore,
                    'details'             => json_encode([
                        'metode' => 'RIASEC 45% + CHC 55%',
                        'profil_jurusan' => $scoresPerCriteria,
                    ]),
                    'riasec_scores'       => $riasecScores,
                    'chc_scores'          => $chcScores,
                    'answers'             => $answers,
                    'recommended_cluster' => $recommendedCluster
                ]
            )
        );

        Session::forget('fase1_answers');
        Session::forget('fase2_answers');
        Session::forget('fase3_answers');
        Session::forget('fase4_answers');
        Session::forget('fase4_warning_seen');
        $this->resetQuestionOrder();
    }

    private function getAssessmentQuestions(int $fase, bool $withCriteria = false)
    {
        $query = $withCriteria ? Question::with('criteria') : Question::query();
        $questions = $query->where('fase', $fase)->get();
        $sessionKey = 'assessment_fase_' . $fase . '_question_order';
        $storedOrder = session($sessionKey);

        if (!is_array($storedOrder)) {
            $storedOrder = $questions->pluck('id')->shuffle()->values()->all();
            session([$sessionKey => $storedOrder]);
        } else {
            $storedIds = array_map('intval', $storedOrder);
            $currentIds = $questions->pluck('id')->map(fn ($id) => (int) $id)->all();
            $newIds = array_values(array_diff($currentIds, $storedIds));
            $storedOrder = array_values(array_intersect($storedIds, $currentIds));

            if ($newIds) {
                shuffle($newIds);
                $storedOrder = array_merge($storedOrder, $newIds);
            }

            session([$sessionKey => $storedOrder]);
        }

        $positions = array_flip($storedOrder);

        return $questions->sortBy(function ($question) use ($positions) {
            return $positions[$question->id] ?? PHP_INT_MAX;
        })->values();
    }

    private function resetQuestionOrder(): void
    {
        foreach ([1, 2, 3, 4] as $fase) {
            Session::forget('assessment_fase_' . $fase . '_question_order');
        }
    }

    private function calculateProfileMatch(array $studentScores, array $profileWeights): float
    {
        $weightedScore = 0;
        $totalWeight = array_sum($profileWeights);

        if ($totalWeight <= 0) {
            return 0;
        }

        foreach ($profileWeights as $indicator => $weight) {
            $weightedScore += ($studentScores[$indicator] ?? 0) * $weight;
        }

        return round($weightedScore / $totalWeight, 2);
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