<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'namakelas', 
        'peryataan'
    ];
    
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function tugas()
    {
        return $this->belongsToMany(Tugas::class, 'tugas_kelas');
    }
}
