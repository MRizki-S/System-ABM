<?php

namespace App\Models;

use App\Models\User;
use App\Models\Punishment;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = [
        'user_id',
        'tanggal',
        'jenis',
        'keterangan',
        'status_checkout',
        'waktu_masuk',
        'waktu_keluar',
        'latitude',
        'longitude',
        'jangkauan_radius',
    ];
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function punishment()
    {
        return $this->hasOne(Punishment::class);
    }
}
