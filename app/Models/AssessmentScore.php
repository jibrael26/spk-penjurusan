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
        'recommended_cluster'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
