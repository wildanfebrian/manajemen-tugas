<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'mapel_id',
        'filetugas'
    ];

    protected $casts = [
        'filetugas' => 'string'
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'tugas_kelas');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
