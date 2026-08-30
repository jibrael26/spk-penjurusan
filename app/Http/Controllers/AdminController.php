<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use App\Models\User; 
use App\Models\AssessmentScore; 
use App\Models\Question; 

class AdminController extends Controller
{
    public function index() 
    {
        // Perbaikan pemisahan baris agar mudah dibaca
        $totalSiswa = User::where('is_admin', false)->count();$totalSudahTes = AssessmentScore::count();
        
        return view('admin.dashboard', compact('totalSiswa', 'totalSudahTes'));
    }

   public function dataSiswa() 
    {
        // PERBAIKAN: Mengubah variabel $users menjadi $siswa dan mengurutkan data terbaru
        $siswa = User::where('is_admin', false)->latest()->paginate(10); 
        
        return view('admin.users.index', compact('siswa'));
    }

    public function questions()
    {
        $questions = Question::latest()->paginate(10);
        return view('admin.questions', compact('questions')); 
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'teks_pertanyaan' => 'required|string',
            'kategori'      => 'required|string',
        ]);

        Question::create([
            'teks_pertanyaan' => $request->teks_pertanyaan,
            'kategori' => $request->kategori,
            'tipe_input' => 'radio',
            'opsi_jawaban' => json_encode(['1' => 'Sangat Kurang', '2' => 'Kurang', '3' => 'Cukup', '4' => 'Baik', '5' => 'Sangat Baik'])
        ]);

        return redirect()->back()->with('success', 'Pertanyaan manual berhasil ditambahkan!');
    }

    public function generateQuestions(Request $request)
    {
        $request->validate([
            'teks_mentah' => 'required|string|min:20'
        ]);

        // Perbaikan pemisahan baris variabel
        $teksMentah = $request->input('teks_mentah');$apiKey = trim(env('GEMINI_API_KEY')); 

        $prompt = "Sebagai ahli psikometrik, baca teks referensi berikut:\n\n" . 
                $teksMentah . "\n\n" .
                "Tugas Anda: Buat tepat 30 butir pertanyaan kuesioner berdasarkan teks tersebut. " .
                "Setiap pertanyaan harus memiliki 5 kriteria jawaban (Skala Likert 1-5). " .
                "Output HARUS berupa array JSON murni tanpa tag markdown (```json). " .
                "Gunakan format ini persis:\n" .
                "[\n" .
                "  {\n" .
                "    \"kategori\": \"Nama Kategori\",\n" .
                "    \"teks_pertanyaan\": \"Isi pertanyaan di sini?\",\n" .
                "    \"tipe_input\": \"radio\",\n" .
                "    \"opsi_jawaban\": {\"1\": \"Sangat Kurang\", \"2\": \"Kurang\", \"3\": \"Cukup\", \"4\": \"Baik\", \"5\": \"Sangat Baik\"}\n" .
                "  }\n" .
                "]";

        try {
            // Perbaikan URL endpoint API Gemini yang sebelumnya memiliki format markdown ganda
            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type'   => 'application/json',
            ])->post('[https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent](https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent)', [
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

            $aiText = $responseData['candidates'][0]['content']['parts'][0]['text'];
            $aiText = trim(str_replace(['```json', '```'], '', $aiText)); 
            $questionsArray = json_decode($aiText, true);

            if (!$questionsArray || !is_array($questionsArray)) {
                return back()->with('error', 'Gagal memparsing respon AI. Format JSON tidak sesuai.');
            }

            foreach ($questionsArray as $q) {
                Question::create([
                    'kategori' => $q['kategori'] ?? 'Umum',
                    'teks_pertanyaan' => $q['teks_pertanyaan'],
                    'tipe_input' => $q['tipe_input'] ?? 'radio',
                    'opsi_jawaban' => json_encode($q['opsi_jawaban']), 
                ]);
            }

            return back()->with('success', count($questionsArray) . ' Pertanyaan berhasil digenerate otomatis!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}