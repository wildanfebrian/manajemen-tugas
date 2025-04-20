<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $guarded = ['id'];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
