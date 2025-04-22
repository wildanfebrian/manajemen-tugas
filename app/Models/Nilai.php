<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'siswa_id',
        'tugas_id',
        'hasil',
        'tanggal',
        'status',
    ];
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
