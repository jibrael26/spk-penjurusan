<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use App\Exports\AssessmentExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User; 
use App\Models\AssessmentScore; 
use App\Models\Question; 
use App\Models\Criteria; 

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

    public function dataSiswa() 
    {
        // 1. Data paginasi khusus siswa (admin otomatis tidak tampil di tabel siswa)
        $siswa = User::where('is_admin', false)
            ->with('assessmentScore')
            ->latest()
            ->paginate(10); 

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
        // 4. LOGIKA K-MEANS CLUSTERING (MURNI SISWA)
        // ==========================================
        $siswaScores = User::where('is_admin', false)
            ->has('assessmentScore')
            ->with('assessmentScore')
            ->get();
        
        $datasetKMeans = [];
        foreach ($siswaScores as $s) {$features = [
                (float) ($s->assessmentScore->k01 ?? 0),
                (float) ($s->assessmentScore->k02 ?? 0),
                (float) ($s->assessmentScore->k03 ?? 0),
                (float) ($s->assessmentScore->k04 ?? 0),
                (float) ($s->assessmentScore->k05 ?? 0),
            ];

            $datasetKMeans[] = [
                'user' => $s,
                'features' => $features
            ];
        }

        $k = min(3, count($datasetKMeans));
        $hasilCluster = $this->kMeansClustering($datasetKMeans, $k);

        return view('admin.users.index', compact(
            'siswa', 
            'totalSiswa', 
            'sudahAssessment', 
            'belumAssessment', 
            'chartLabels', 
            'chartData', 
            'jurusanTerbanyak', 
            'hasilCluster'
        ));
    }

    // ==========================================
    // MANAJEMEN DATA SISWA (UPDATE & DELETE)
    // ==========================================

    /**
     * Memperbarui data siswa dan rekomendasi jurusannya.
     */
    public function updateUser(Request $request,$id)
    {
        $siswa = User::findOrFail($id);

    $validated = $request->validate([
        'name'              => 'required|string|max:255',
        'email'             => 'required|email|unique:users,email,' . $siswa->id,
        'recommended_major' => 'nullable|string|max:255',
        'password'          => 'nullable|min:8', // Jadikan nullable
    ]);

    // Update nama, email, dsb
    $siswa->name = $validated['name'];
    $siswa->email = $validated['email'];
    $siswa->recommended_major = $validated['recommended_cluster'] ?? null; // Pastikan field ini ada di tabel users

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
        if ($user->assessmentScore) {$user->assessmentScore->delete();
        }

        $user->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus secara permanen!');
    }

    // ==========================================
    // FUNGSI HELPER ALGORITMA K-MEANS
    // ==========================================
    private function kMeansClustering($data,$k)
    {
        if (empty($data) || $k < 1) return [];

        $centroids = [];$randomKeys = array_rand($data,$k);
        if (!is_array($randomKeys)) $randomKeys = [$randomKeys];

        foreach ($randomKeys as $key) {$centroids[] = $data[$key]['features'];
        }

        $clusters = [];
        $oldClusters = [];$maxIter = 100;

        for ($iter = 0; $iter < $maxIter; $iter++) {
            $clusters = array_fill(0,$k, []);

            foreach ($data as $item) {$minDist = null;
                $closestCentroid = 0;

                foreach ($centroids as $idx =>$centroid) {
                    $dist =$this->euclideanDistance($item['features'],$centroid);
                    if ($minDist === null || $dist <$minDist) {
                        $minDist =$dist;
                        $closestCentroid =$idx;
                    }
                }
                $clusters[$closestCentroid][] =$item;
            }

            if ($clusters ===$oldClusters) break;
            $oldClusters =$clusters;

            foreach ($clusters as $idx =>$cluster) {
                if (count($cluster) > 0) {
                    $numFeatures = count($centroids[0]);
                    $newCentroid = array_fill(0,$numFeatures, 0);

                    foreach ($cluster as$item) {
                        foreach ($item['features'] as$dim => $val) {$newCentroid[$dim] +=$val;
                        }
                    }

                    foreach ($newCentroid as$dim => $val) {$centroids[$idx][$dim] = $val / count($cluster);
                    }
                }
            }
        }

        return $clusters;
    }

    private function euclideanDistance($point1,$point2)
    {
        $sum = 0;
        foreach ($point1 as $i =>$val) {
            $sum += pow($val - $point2[$i], 2);
        }
        return sqrt($sum);
    }

    // ==========================================
    // MANAJEMEN PERTANYAAN (BANK SOAL)
    // ==========================================
    public function questions()
    {
        $questions = Question::with('criteria')->latest()->paginate(10);$criteria = Criteria::all();        
        return view('admin.questions', compact('questions', 'criteria')); 
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'teks_pertanyaan' => 'required|string',
            'criteria_id'     => 'required|exists:criteria,id',
            'fase'            => 'required|in:1,2',
            'kode_indikator'  => 'nullable|string|max:10',
            'kunci_jawaban'   => 'nullable|string|max:10',
            'gambar'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath =$request->file('gambar')->store('soal_gambar', 'public');
        }

        $opsi_jawaban = ($request->fase == 1) ? null : [1 => 'Opsi A', 2 => 'Opsi B', 3 => 'Opsi C', 4 => 'Opsi D'];

        Question::create([
            'criteria_id'     => $request->criteria_id,
            'teks_pertanyaan' => $request->teks_pertanyaan,
            'gambar'          => $gambarPath,
            'fase'            => $request->fase,
            'tipe_opsi'       => 'text',
            'opsi_jawaban'    => $opsi_jawaban,
            'kode_indikator'  => $request->kode_indikator,
            'kunci_jawaban'   => $request->kunci_jawaban,
        ]);

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan!');
    }

    public function generateQuestions(Request $request)
    {
        $request->validate([
            'teks_mentah' => 'required|string|min:20',
            'criteria_id' => 'required|exists:criteria,id'
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
                    'criteria_id'     => $request->criteria_id,
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
        $criteria = Criteria::all();
        
        return view('admin.questions_edit', compact('question', 'criteria'));
    }

    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'teks_pertanyaan' => 'required|string',
            'criteria_id'     => 'required|exists:criteria,id',
            'fase'            => 'required|in:1,2',
            'opsi_jawaban'    => 'nullable|array', 
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
            'criteria_id'     => $request->criteria_id,
            'teks_pertanyaan' => $request->teks_pertanyaan,
            'fase'            => $request->fase,
            'opsi_jawaban'    => empty($opsiJawaban) ? null : $opsiJawaban, 
            'gambar'          => $gambarPath,
        ]);

        return redirect()->route('admin.pertanyaan')->with('success', 'Pertanyaan beserta opsi jawaban berhasil diperbarui!');
    }

    public function exportAssessment()
    {
        return Excel::download(new AssessmentExport, 'laporan_hasil_penjurusan.xlsx');
    }
}