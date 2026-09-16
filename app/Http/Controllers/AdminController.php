<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use App\Models\User; 
use App\Models\AssessmentScore; 
use App\Models\Question; 
use App\Models\Criteria; // Ditambahkan agar bisa memanggil jurusan

class AdminController extends Controller
{
    public function index() 
    {
        $totalSiswa = User::where('is_admin', false)->count();
        // Menghitung user unik yang sudah melakukan tes
        $totalSudahTes = AssessmentScore::distinct('user_id')->count('user_id'); 
        return view('admin.dashboard', compact('totalSiswa', 'totalSudahTes'));
    }

    public function dataSiswa() 
    {
        $users = User::where('is_admin', false)->paginate(10); 
        return view('admin.users.index', compact('users'));
    }

    public function questions()
    {
        // Menggunakan with('criteria') agar nama jurusan bisa ditampilkan di tabel
        $questions = Question::with('criteria')->latest()->paginate(10);
        // Mengirim data kriteria untuk opsi dropdown saat tambah soal manual/AI
        $criteria = Criteria::all();
        return view('admin.questions', compact('questions', 'criteria')); 
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'teks_pertanyaan' => 'required|string',
            'criteria_id'     => 'required|exists:criteria,id',
            'fase'            => 'required|in:1,2',
        ]);

        Question::create([
            'criteria_id'     => $request->criteria_id,
            'teks_pertanyaan' => $request->teks_pertanyaan,
            'fase'            => $request->fase,
            'tipe_opsi'       => 'text',
            // Default opsi untuk Fase 1 (Angket). Jika Fase 2, admin bisa edit nanti.
            'opsi_jawaban'    => $request->fase == 1 ? null : [
                1 => 'Sangat Kurang', 2 => 'Kurang', 3 => 'Cukup', 4 => 'Baik', 5 => 'Sangat Baik'
            ]
        ]);

        return redirect()->back()->with('success', 'Pertanyaan manual berhasil ditambahkan!');
    }

    public function generateQuestions(Request $request)
    {
        $request->validate([
            'teks_mentah' => 'required|string|min:20',
            'criteria_id' => 'required|exists:criteria,id' // AI akan membuat soal khusus untuk jurusan ini
        ]);

        $apiKey = trim(env('GEMINI_API_KEY')); 

        if (empty($apiKey)) {
            return back()->with('error', 'Sistem gagal membaca GEMINI_API_KEY. Pastikan kunci terisi di .env.');
        }

        $teksMentah =$request->input('teks_mentah');
        
        // Prompt dioptimalkan agar output JSON selaras dengan struktur tabel kita
        $prompt = "Sebagai ahli psikometrik, baca teks referensi berikut:\n\n" . 
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
                    'fase'            => 2, // AI di-set untuk menghasilkan soal pilihan (Fase 2)
                    'tipe_opsi'       => 'text',
                    'opsi_jawaban'    => $q['opsi_jawaban'], // Tidak perlu json_encode karena ada $casts di Model
                ]);
            }

            return back()->with('success', count($questionsArray) . ' Pertanyaan AI berhasil masuk ke bank soal!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem HTTP: ' . $e->getMessage());
        }
    }

    // ==========================================
    // TAMBAHAN: FITUR HAPUS DAN EDIT
    // ==========================================

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
        // Akan merender halaman form edit yang akan kita buat nanti
        return view('admin.questions_edit', compact('question', 'criteria'));
    }
}