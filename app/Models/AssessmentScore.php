<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentScore extends Model
{
    use HasFactory;

    // Daftar kolom yang boleh diisi melalui form
    protected $fillable = [
        'user_id',
        'realistic',
        'investigative',
        'artistic',
        'social',
        'enterprising',
        'conventional',
        'numerical_ability',
        'verbal_reasoning',
        'mechanical_reasoning',
        'recommended_cluster'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
