<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = ['id'];
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
