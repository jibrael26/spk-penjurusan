<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            'K01' => 'Agribisnis Tanaman Pangan dan Hortikultura',
            'K02' => 'Agribisnis Pengolahan Hasil Pertanian',
            'K03' => 'Akuntansi dan Keuangan Lembaga',
            'K04' => 'Teknik Kendaraan Ringan Otomotif',
            'K05' => 'Teknik Komputer dan Jaringan',
        ];

        foreach ($criteria as $kode => $nama) {
            Criteria::updateOrCreate(
                ['kode_kriteria' => $kode],
                ['nama_kriteria' => $nama]
            );
        }
    }
}
