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
            'R',
            'I',
            'A',
            'S',
            'E',
            'C',
            'GF',
            'GC',
            'GV',
            'GQ',
            'GWM',
            'GS',
            'Jurusan',
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
            (int) round($riasecScores['r'] ?? 0),
            (int) round($riasecScores['i'] ?? 0),
            (int) round($riasecScores['a'] ?? 0),
            (int) round($riasecScores['s'] ?? 0),
            (int) round($riasecScores['e'] ?? 0),
            (int) round($riasecScores['c'] ?? 0),
            (int) round($chcScores['gf'] ?? 0),
            (int) round($chcScores['gc'] ?? 0),
            (int) round($chcScores['gv'] ?? 0),
            (int) round($chcScores['gq'] ?? 0),
            (int) round($chcScores['gwm'] ?? 0),
            (int) round($chcScores['gs'] ?? 0),
            $assessment->recommended_cluster ?? $assessment->recommended_major ?? 'Belum Assessment',
        ];
    }
}