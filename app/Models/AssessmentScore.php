<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'k01',
        'k02',
        'k03',
        'k04',
        'k05',
        'recommended_cluster', // Menyimpan hasil rekomendasi K-Means
        'criteria_id',         // Menyimpan ID Jurusan Rekomendasi (berdasarkan metode bobot)
        'score',               // Menyimpan skor total/akhir (berdasarkan metode bobot)
        'details',              // Menyimpan riwayat rincian perhitungan minat & bakat dalam format JSON
        'riasec_scores'        // Menyimpan persentase skor enam dimensi RIASEC
    ];

    protected $casts = [
        'riasec_scores' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi opsional jika Anda ingin mengambil data jurusan yang direkomendasikan
     */
    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }
}