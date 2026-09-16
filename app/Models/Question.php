<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'criteria_id',
        'teks_pertanyaan',
        'fase',
        'tipe_opsi',
        'opsi_jawaban',
        'bobot',
    ];

    // Otomatis convert JSON dari DB menjadi Array PHP
    protected $casts = [
        'opsi_jawaban' => 'array', 
    ];

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
}