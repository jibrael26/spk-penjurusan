<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    // Tambahkan baris ini untuk mem-bypass tebakan otomatis Laravel
    protected $table = 'criteria';

    protected $fillable = ['kode_kriteria', 'nama_kriteria'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}