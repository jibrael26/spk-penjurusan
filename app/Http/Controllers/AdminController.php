<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use App\Exports\AssessmentExport;
use App\Exports\AssessmentAnswersExport;
use App\Exports\KMeansClusteringExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User; 
use App\Models\AssessmentScore; 
use App\Models\Question; 
use App\Models\Criteria; 
use App\Services\KMeansService;

class AdminController extends Controller
{
    public function index()
{
    // Data statistik utama
    $totalSiswa = User::where('is_admin', 0)->count(); 
    $totalSoal = Question::count();
    $totalSudahTes = AssessmentScore::distinct('user_id')->count();

    // 1. Agregasi data grafik distribusi jurusan dari database
    $distribusiJurusan = AssessmentScore::selectRaw('recommended_cluster, count(*) as total')
        ->groupBy('recommended_cluster')
        ->pluck('total', 'recommended_cluster');

    $labels = $distribusiJurusan->keys()->values()->all();
    $totals = $distribusiJurusan->values()->all();

    // Format array ke JSON agar siap dikonsumsi Chart.js di Blade
    $chartLabels = json_encode($labels);
    $chartData = json_encode($totals);

    $scatterByCluster = [];
    $scatterCentroids = [];
    $scatterAssessments = AssessmentScore::with('user')
        ->whereNotNull('recommended_cluster')
        ->whereHas('user', function ($query) {
            $query->where('is_admin', false);
        })
        ->get();
    $kmeansService = app(KMeansService::class);
    foreach ($scatterAssessments as $assessment) {
        $vector = $kmeansService->vectorFromAssessment($assessment);
        if (!$vector) {
            continue;
        }

        $cluster = $assessment->recommended_cluster;
        $point = $kmeansService->projectTo2D($vector);
        $scatterByCluster[$cluster][] = [
            'x' => $point['x'],
            'y' => $point['y'],
            'name' => $assessment->user->name ?? 'Siswa',
        ];
    }

    foreach ($scatterByCluster as $cluster => $points) {
        $scatterCentroids[] = [
            'x' => round((float) collect($points)->avg('x'), 2),
            'y' => round((float) collect($points)->avg('y'), 2),
            'name' => $cluster,
        ];
    }

    $scatterSeries = collect($scatterByCluster)
        ->map(fn (array $points, string $cluster) => [
            'name' => $cluster,
            'data' => $points,
        ])
        ->values()
        ->all();

    // 2. Data 5 siswa terbaru yang menyelesaikan tes beserta relasi user
    $recentTests = AssessmentScore::with('user')
        ->whereHas('user', function ($query) {
            $query->where('is_admin', false);
        })
        ->latest()
        ->take(5)
        ->get();

    return view('admin.dashboard', compact(
        'totalSiswa', 
        'totalSoal', 
        'totalSudahTes', 
        'labels',
        'totals',
        'chartLabels', 
        'chartData', 
        'scatterSeries',
        'scatterCentroids',
        'recentTests'
    ));
}

public function dashboard()
    {
        // CONTOH: Mengambil data jumlah siswa berdasarkan hasil penjurusan
        // Sesuaikan query ini dengan struktur database aplikasi SPK kamu
        
        $dataPenjurusan = DB::table('users')
            ->select('jurusan', DB::raw('count(*) as total'))
            ->whereNotNull('jurusan')
            ->groupBy('jurusan')
            ->get();

        // Memisahkan data menjadi array untuk label (nama jurusan) dan total (jumlah)
        $labels = $dataPenjurusan->pluck('jurusan')->toArray();
        $totals = $dataPenjurusan->pluck('total')->toArray();
        $chartLabels = json_encode($labels);
        $chartData = json_encode($totals);

        // Mengirim data ke view
        return view('admin.dashboard', compact('labels', 'totals', 'chartLabels', 'chartData'));
    }

    public function dataSiswa(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        // 1. Data paginasi khusus siswa (admin otomatis tidak tampil di tabel siswa)
        $siswa = User::where('is_admin', false)
            ->with('assessmentScore')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('assessmentScore', function ($query) use ($search) {
                            $query->where('recommended_cluster', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $questionCatalog = Question::orderBy('fase')->orderBy('id')->get([
            'id', 'fase', 'kode_indikator', 'teks_pertanyaan', 'opsi_jawaban', 'kunci_jawaban'
        ]);

        // ==========================================
        // 2. DATA GRAFIK: STATUS ASSESSMENT (MURNI SISWA)
        // ==========================================
        $totalSiswa = User::where('is_admin', false)->count();
        
        // Hitung assessment hanya untuk user yang 'is_admin' = false
        $sudahAssessment = AssessmentScore::whereHas('user', function($query) {$query->where('is_admin', false);
        })->distinct('user_id')->count('user_id');
        
        $belumAssessment = $totalSiswa -$sudahAssessment;

        // ==========================================
        // 3. DATA GRAFIK: REKOMENDASI JURUSAN (MURNI SISWA)
        // ==========================================
        $chartLabels = collect([]);
        $chartData = collect([]);$jurusanTerbanyak = null;

        try {
            // Melakukan join atau filter agar assessment milik admin tidak ikut terhitung dalam grafik rekomendasi
            $statistikJurusan = AssessmentScore::whereHas('user', function($query) {$query->where('is_admin', false);
                })
                ->select('recommended_cluster', DB::raw('count(*) as total'))
                ->whereNotNull('recommended_cluster')
                ->groupBy('recommended_cluster')
                ->orderBy('total', 'desc')
                ->get();

            $chartLabels =$statistikJurusan->pluck('recommended_cluster');
            $chartData =$statistikJurusan->pluck('total');
            $jurusanTerbanyak =$statistikJurusan->first();
        } catch (\Exception $e) {
            // Fallback aman jika kolom belum tersedia
        }

        // ==========================================
        // 4. PENGELOMPOKAN SISWA BERDASARKAN HASIL K-MEANS
        // ==========================================
        $siswaScores = User::where('is_admin', false)
            ->has('assessmentScore')
            ->with('assessmentScore')
            ->get();

        // recommended_cluster adalah nama jurusan hasil K-Means yang disimpan
        // saat siswa menyelesaikan assessment.
        $hasilCluster = $siswaScores
            ->filter(function ($siswa) {
                return !empty($siswa->assessmentScore->recommended_cluster);
            })
            ->groupBy(function ($siswa) {
                return $siswa->assessmentScore->recommended_cluster;
            })
            ->map(function ($siswaDalamJurusan) {
                return $siswaDalamJurusan->map(function ($siswa) {
                    return ['user' => $siswa];
                })->values()->all();
            });

        $performanceRecords = $siswaScores
            ->map(fn ($siswa) => data_get($siswa->assessmentScore->details, 'kinerja_rekomendasi'))
            ->filter(fn ($performance) => is_array($performance));
        $clusteringSummary = [
            'total' => $performanceRecords->count(),
            'rata_rata_skor' => round((float) $performanceRecords->avg('skor_tertinggi'), 2),
            'rata_rata_margin' => round((float) $performanceRecords->avg('margin_kandidat_kedua'), 2),
            'rata_rata_keyakinan' => round((float) $performanceRecords->avg('tingkat_keyakinan'), 2),
            'keyakinan_rendah' => $performanceRecords->filter(
                fn ($performance) => (float) ($performance['tingkat_keyakinan'] ?? 0) < 50
            )->count(),
        ];

        return view('admin.users.index', compact(
            'siswa', 
            'totalSiswa', 
            'sudahAssessment', 
            'belumAssessment', 
            'chartLabels', 
            'chartData', 
            'jurusanTerbanyak', 
            'hasilCluster'
            , 'clusteringSummary'
            , 'questionCatalog'
            , 'search'
        ));
    }

    public function exportKMeansClustering()
    {
        return Excel::download(
            new KMeansClusteringExport(),
            'hasil-clustering-kmeans-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }

    public function exportAssessmentAnswers(?int $userId = null)
    {
        if ($userId !== null) {
            User::where('is_admin', false)->findOrFail($userId);
        }

        $filename = $userId
            ? 'jawaban-assessment-siswa-' . $userId . '-' . now()->format('Y-m-d-His') . '.xlsx'
            : 'jawaban-assessment-seluruh-siswa-' . now()->format('Y-m-d-His') . '.xlsx';

        return Excel::download(new AssessmentAnswersExport($userId), $filename);
    }

    // ==========================================
    // MANAJEMEN DATA SISWA (UPDATE & DELETE)
    // ==========================================

    /**
     * Memperbarui data dasar siswa tanpa mengubah rekomendasi assessment.
     */
    public function updateUser(Request $request,$id)
    {
        $siswa = User::findOrFail($id);

    $validated = $request->validate([
        'name'              => 'required|string|max:255',
        'email'             => 'required|email|unique:users,email,' . $siswa->id,
        'password'          => 'nullable|min:8', // Jadikan nullable
    ]);

    // Update nama, email, dsb
    $siswa->name = $validated['name'];
    $siswa->email = $validated['email'];

    // Hanya hash dan update password JIKA input password tidak kosong
    if ($request->filled('password')) {
        $siswa->password = Hash::make($request->password);
    }

    $siswa->save();

    return redirect()->back()->with('success', 'Data siswa berhasil diperbarui!'); }

    /**
     * Menghapus data siswa beserta data hasilnya.
     */
    public function destroyUser($id)
    {
        $user = User::where('is_admin', false)->findOrFail($id);

        // Hapus data hasil assessment terkait jika ada
        $user->assessmentScores()->delete();

        $user->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus secara permanen!');
    }

    // ==========================================
    // MANAJEMEN PERTANYAAN (BANK SOAL)
    // ==========================================
    public function questions(Request $request)
    {
        $questions = Question::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($questionQuery) use ($search) {
                    $questionQuery->where('teks_pertanyaan', 'like', '%' . $search . '%')
                        ->orWhere('kode_indikator', 'like', '%' . $search . '%');
                });
            })
            ->when($request->filled('fase') && in_array($request->input('fase'), ['1', '2', '3', '4'], true), function ($query) use ($request) {
                $query->where('fase', $request->input('fase'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.questions', compact('questions'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'teks_pertanyaan' => 'required|string',
            'teks_awal'       => 'nullable|string|max:1000',
            'fase'            => 'required|in:1,2,3,4',
            'opsi_jawaban'    => 'nullable|array',
            'opsi_jawaban.*'  => 'nullable|string|max:1000',
            'bobot'           => 'nullable|integer|min:1|max:3600',
            'sembunyikan_teks' => 'nullable|boolean',
            'kode_indikator'  => 'required|string|in:R,I,A,S,E,C,Gf,Gc,Gv,Gq,Gwm,Gs',
            'kunci_jawaban'   => 'nullable|string|max:10',
            'gambar'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath =$request->file('gambar')->store('soal_gambar', 'public');
        }

        $opsi_jawaban = $request->fase == 1
            ? null
            : collect($request->input('opsi_jawaban', []))
                ->map(fn ($option) => trim((string) $option))
                ->filter(fn ($option) => $option !== '')
                ->all();

        Question::create([
            'teks_pertanyaan' => $request->teks_pertanyaan,
            'teks_awal'       => $request->fase == 4 ? $request->teks_awal : null,
            'gambar'          => $gambarPath,
            'fase'            => $request->fase,
            'tipe_opsi'       => 'text',
            'opsi_jawaban'    => $opsi_jawaban ?: null,
            'kode_indikator'  => $request->kode_indikator,
            'kunci_jawaban'   => $request->kunci_jawaban,
            'bobot'           => $request->fase == 4 ? ($request->bobot ?? 12) : null,
            'sembunyikan_teks' => $request->fase == 4 && $request->boolean('sembunyikan_teks'),
        ]);

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan!');
    }

    public function generateQuestions(Request $request)
    {
        $request->validate([
            'teks_mentah' => 'required|string|min:20',
        ]);

        $apiKey = trim(env('GEMINI_API_KEY')); 

        if (empty($apiKey)) {
            return back()->with('error', 'Sistem gagal membaca GEMINI_API_KEY. Pastikan kunci terisi di .env.');
        }

        $teksMentah = $request->input('teks_mentah');$prompt = "Sebagai ahli psikometrik, baca teks referensi berikut:\n\n" . 
                  $teksMentah . "\n\n" .
                  "Tugas Anda: Buat tepat 10 butir pertanyaan kuesioner berdasarkan teks tersebut. " .
                  "Setiap pertanyaan harus memiliki 5 kriteria jawaban (Skala Likert 1-5). " .
                  "Output HARUS berupa array JSON murni tanpa tag markdown (```json). " .
                  "Gunakan format ini persis:\n" .
                  "[\n" .
                  "  {\n" .
                  "    \"teks_pertanyaan\": \"Isi pertanyaan studi kasus di sini?\",\n" .
                  "    \"opsi_jawaban\": {\"1\": \"Sangat Kurang\", \"2\": \"Kurang\", \"3\": \"Cukup\", \"4\": \"Baik\", \"5\": \"Sangat Baik\"}\n" .
                  "  }\n" .
                  "]";

        try {
            $url = "[https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=](https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=)" . $apiKey;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $responseData = $response->json();

            if (isset($responseData['error'])) {
                return back()->with('error', 'API Error: ' . $responseData['error']['message']);
            }

            $aiText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $aiText = trim(str_replace(['```json', '```'], '', $aiText)); 
            
            $questionsArray = json_decode($aiText, true);

            if (!$questionsArray || !is_array($questionsArray)) {
                return back()->with('error', 'Gagal memparsing respon AI. Format JSON tidak sesuai.');
            }

            foreach ($questionsArray as $q) {
                Question::create([
                    'teks_pertanyaan' => $q['teks_pertanyaan'],
                    'fase'            => 2,
                    'tipe_opsi'       => 'text',
                    'opsi_jawaban'    => $q['opsi_jawaban'],
                ]);
            }

            return back()->with('success', count($questionsArray) . ' Pertanyaan AI berhasil masuk ke bank soal!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem HTTP: ' . $e->getMessage());
        }
    }

    public function destroyQuestion($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->back()->with('success', 'Data pertanyaan berhasil dihapus secara permanen!');
    }

    public function editQuestion($id)
    {
        $question = Question::findOrFail($id);
        
        return view('admin.questions_edit', compact('question'));
    }

    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'teks_pertanyaan' => 'required|string',
            'teks_awal'       => 'nullable|string|max:1000',
            'fase'            => 'required|in:1,2,3,4',
            'bobot'           => 'nullable|integer|min:1|max:3600',
            'sembunyikan_teks' => 'nullable|boolean',
            'opsi_jawaban'    => 'nullable|array', 
            'opsi_jawaban.*'  => 'nullable|string|max:1000',
            'kode_indikator'  => 'required|string|in:R,I,A,S,E,C,Gf,Gc,Gv,Gq,Gwm,Gs',
            'kunci_jawaban'   => 'nullable|string|max:10',
            'gambar'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $question = Question::findOrFail($id);

        $opsiJawaban = $request->opsi_jawaban;
        if (is_array($opsiJawaban)) {
            $opsiJawaban = array_filter($opsiJawaban, function($value) {
                return !is_null($value) && $value !== '';
            });
        }

        $gambarPath = $question->gambar; 

        if ($request->hasFile('gambar')) {
            if ($question->gambar && Storage::disk('public')->exists($question->gambar)) {
                Storage::disk('public')->delete($question->gambar);
            }
            $gambarPath = $request->file('gambar')->store('soal_gambar', 'public');
        }

        $question->update([
            'teks_pertanyaan' => $request->teks_pertanyaan,
            'teks_awal'       => $request->fase == 4 ? $request->teks_awal : null,
            'fase'            => $request->fase,
            'kode_indikator'  => $request->kode_indikator,
            'opsi_jawaban'    => empty($opsiJawaban) ? null : $opsiJawaban, 
            'kunci_jawaban'   => $request->fase == 1 ? null : $request->kunci_jawaban,
            'gambar'          => $gambarPath,
            'bobot'           => $request->fase == 4 ? ($request->bobot ?? 12) : null,
            'sembunyikan_teks' => $request->fase == 4 && $request->boolean('sembunyikan_teks'),
        ]);

        return redirect()->route('admin.pertanyaan')->with('success', 'Pertanyaan beserta opsi jawaban berhasil diperbarui!');
    }

    public function exportAssessment()
    {
        return Excel::download(new AssessmentExport, 'laporan_hasil_penjurusan.xlsx');
    }
}