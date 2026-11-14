<?php

namespace App\Exports;

use App\Models\AssessmentScore;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssessmentExport implements FromCollection, WithHeadings, WithMapping
{
    private int $rowNumber = 0;

    /**
     * Mengambil data assessment beserta relasi user
     */
    public function collection(): Collection
    {
        return AssessmentScore::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'Nama Siswa',
            'Email',
            'R (1-5)',
            'I (1-5)',
            'A (1-5)',
            'S (1-5)',
            'E (1-5)',
            'C (1-5)',
            'GF (1-5)',
            'GC (1-5)',
            'GV (1-5)',
            'GQ (1-5)',
            'GWM (1-5)',
            'GS (1-5)',
            'Program Keahlian',
        ];
    }

    public function map($assessment): array
    {
        $riasecScores = $assessment->riasec_scores ?? [];
        $chcScores = $assessment->chc_scores ?? [];

        return [
            ++$this->rowNumber,
            $assessment->user->name ?? 'N/A',
            $assessment->user->email ?? 'N/A',
            $this->toFivePointScale($riasecScores['r'] ?? 0),
            $this->toFivePointScale($riasecScores['i'] ?? 0),
            $this->toFivePointScale($riasecScores['a'] ?? 0),
            $this->toFivePointScale($riasecScores['s'] ?? 0),
            $this->toFivePointScale($riasecScores['e'] ?? 0),
            $this->toFivePointScale($riasecScores['c'] ?? 0),
            $this->toFivePointScale($chcScores['gf'] ?? 0),
            $this->toFivePointScale($chcScores['gc'] ?? 0),
            $this->toFivePointScale($chcScores['gv'] ?? 0),
            $this->toFivePointScale($chcScores['gq'] ?? 0),
            $this->toFivePointScale($chcScores['gwm'] ?? 0),
            $this->toFivePointScale($chcScores['gs'] ?? 0),
            $assessment->recommended_cluster ?? $assessment->recommended_major ?? 'Belum Assessment',
        ];
    }

    private function toFivePointScale(mixed $percentage): int
    {
        $percentage = min(100, max(0, (float) $percentage));

        return (int) min(5, max(1, ceil($percentage / 20)));
    }
}