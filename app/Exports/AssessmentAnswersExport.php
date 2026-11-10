<?php

namespace App\Exports;

use App\Models\AssessmentScore;
use App\Models\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssessmentAnswersExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $userId = null)
    {
    }

    public function collection(): Collection
    {
        $scores = AssessmentScore::with('user')
            ->whereHas('user', fn ($query) => $query->where('is_admin', false))
            ->when($this->userId, fn ($query) => $query->where('user_id', $this->userId))
            ->orderBy('user_id')
            ->get();

        $questionIds = $scores
            ->flatMap(fn ($score) => array_keys($score->answers ?? []))
            ->unique()
            ->values();

        $questions = Question::whereIn('id', $questionIds)->get()->keyBy(fn ($question) => (string) $question->id);

        return $scores->flatMap(function ($score) use ($questions) {
            return collect($score->answers ?? [])->sortKeysUsing(function ($first, $second) {
                return (int) $first <=> (int) $second;
            })->map(function ($answer, $questionId) use ($score, $questions) {
                $question = $questions->get((string) $questionId);
                $answerLabel = $this->answerLabel($question, $answer);
                $isCorrect = $question && (int) $question->fase > 1
                    ? ((string) $answer === (string) $question->kunci_jawaban ? 'Benar' : 'Salah')
                    : '-';

                return [
                    $score->user->id ?? '-',
                    $score->user->name ?? 'N/A',
                    $score->user->email ?? 'N/A',
                    $question?->fase ?? '-',
                    $question?->kode_indikator ?? '-',
                    $question?->teks_pertanyaan ?? 'Pertanyaan tidak ditemukan',
                    $answer,
                    $answerLabel,
                    $isCorrect,
                    // $score->created_at?->format('d-m-Y H:i') ?? '-',
                ];
            });
        })->values();
    }

    public function headings(): array
    {
        return [
            'ID Siswa',
            'Nama Siswa',
            'Email',
            'Fase',
            'Indikator',
            'Pertanyaan',
            'Nilai Jawaban',
            'Jawaban',
            'Status', 
            // 'Tanggal Assessment',
        ];
    }

    private function answerLabel(?Question $question, mixed $answer): string
    {
        if (!$question) {
            return '-';
        }

        if ((int) $question->fase === 1) {
            return [
                1 => 'Sangat Tidak Setuju',
                2 => 'Tidak Setuju',
                3 => 'Netral',
                4 => 'Setuju',
                5 => 'Sangat Setuju',
            ][(int) $answer] ?? (string) $answer;
        }

        $options = $question->opsi_jawaban ?? [];

        return (string) ($options[(string) $answer] ?? $options[(int) $answer] ?? $answer);
    }
}
