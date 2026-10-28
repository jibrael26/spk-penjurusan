<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'criteria_id',
        'teks_pertanyaan',
        'teks_awal',
        'tipe_opsi',
        'gambar',
        'fase',
        'opsi_jawaban',
        'bobot',
        'sembunyikan_teks',
        'kode_indikator', // BARU
        'kunci_jawaban'
    ];

    // Otomatis convert JSON dari DB menjadi Array PHP
    protected $casts = [
        'opsi_jawaban' => 'array', 
        'sembunyikan_teks' => 'boolean',
    ];

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
}