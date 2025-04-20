<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $guarded = ['id'];

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
