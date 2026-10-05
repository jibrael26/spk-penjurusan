<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\AssessmentScore;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    /**
     * Relasi utama (1 Siswa = 1 Hasil Assessment)
     */
    public function assessmentScore(): HasOne
    {
        return $this->hasOne(AssessmentScore::class, 'user_id');
    }

    /**
     * Alias plural agar aman jika ada pemanggilan 'assessmentScores' di bagian kode lain
     */
    public function assessmentScores(): HasOne
    {
        return $this->hasOne(AssessmentScore::class, 'user_id');
    }
}