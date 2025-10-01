<?php

namespace App\Models;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Database\Eloquent\Model;

class Perumahaan extends Model
{
    protected $table = 'perumahaan';
    protected $fillable = [
        'nama',
        'alamat',
        'latitude',
        'longitude',
        'group_id_wa', // bisa untuk notifikasi WA
    ];

    /**
     * Relasi ke User
     */
    public function users()
    {
        return $this->hasMany(User::class, 'perumahaan_id');
    }

    /**
     * Relasi ke Absensi
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'perumahaan_id');
    }
}
