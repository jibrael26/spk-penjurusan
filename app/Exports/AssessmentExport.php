<?php

namespace App\Exports;

use App\Models\AssessmentScore;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssessmentExport implements FromCollection, WithHeadings, WithMapping
{
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
            'ID',
            'Nama Siswa',
            'Email',
            'Skor C1',
            'Skor C2',
            'Skor C3',
            'Rekomendasi Jurusan',
            'Tanggal Asesmen'
        ];
    }

    public function map($assessment): array
    {
        return [
            $assessment->id,
            $assessment->user->name ?? 'N/A',
            $assessment->user->email ?? 'N/A',
            $assessment->score_c1 ?? 0,
            $assessment->score_c2 ?? 0,
            $assessment->score_c3 ?? 0,
            $assessment->recommended_cluster ?? $assessment->recommended_major ?? 'Belum Assessment',
            $assessment->created_at ? $assessment->created_at->format('d-m-Y H:i') : 'N/A',
        ];
    }
}