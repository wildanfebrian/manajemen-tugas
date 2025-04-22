<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'nis',
        'kelas_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the kelas that the siswa belongs to.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
    
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
    
    /**
     * Get the nilai for the siswa.
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
