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
            'R (0-100)',
            'I (0-100)',
            'A (0-100)',
            'S (0-100)',
            'E (0-100)',
            'C (0-100)',
            'GF (0-100)',
            'GC (0-100)',
            'GV (0-100)',
            'GQ (0-100)',
            'GWM (0-100)',
            'GS (0-100)',
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
            $this->toPercentage($riasecScores['r'] ?? 0),
            $this->toPercentage($riasecScores['i'] ?? 0),
            $this->toPercentage($riasecScores['a'] ?? 0),
            $this->toPercentage($riasecScores['s'] ?? 0),
            $this->toPercentage($riasecScores['e'] ?? 0),
            $this->toPercentage($riasecScores['c'] ?? 0),
            $this->toPercentage($chcScores['gf'] ?? 0),
            $this->toPercentage($chcScores['gc'] ?? 0),
            $this->toPercentage($chcScores['gv'] ?? 0),
            $this->toPercentage($chcScores['gq'] ?? 0),
            $this->toPercentage($chcScores['gwm'] ?? 0),
            $this->toPercentage($chcScores['gs'] ?? 0),
            $assessment->recommended_cluster ?? $assessment->recommended_major ?? 'Belum Assessment',
        ];
    }

    private function toPercentage(mixed $percentage): float
    {
        return round(min(100, max(0, (float) $percentage)), 2);
    }
}