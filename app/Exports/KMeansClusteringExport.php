<?php

namespace App\Exports;

use App\Models\AssessmentScore;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KMeansClusteringExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return AssessmentScore::with('user')
            ->whereNotNull('recommended_cluster')
            ->whereHas('user', function ($query) {
                $query->where('is_admin', false);
            })
            ->orderBy('recommended_cluster')
            ->orderBy('user_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Siswa',
            'Nama Siswa',
            'Email',
            'Rekomendasi Jurusan',
            'Skor K01',
            'Skor K02',
            'Skor K03',
            'Skor K04',
            'Skor K05',
            'Tanggal Assessment',
        ];
    }

    public function map($assessment): array
    {
        return [
            $assessment->user->id ?? 'N/A',
            $assessment->user->name ?? 'N/A',
            $assessment->user->email ?? 'N/A',
            $assessment->recommended_cluster,
            $assessment->k01 ?? 0,
            $assessment->k02 ?? 0,
            $assessment->k03 ?? 0,
            $assessment->k04 ?? 0,
            $assessment->k05 ?? 0,
            $assessment->created_at?->format('d-m-Y H:i') ?? 'N/A',
        ];
    }
}
