<?php

namespace App\Models;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Database\Eloquent\Model;

class Punishment extends Model
{
    protected $table = 'punishment';
    protected $fillable = [
        'user_id',
        'absensi_id',
        'jam_keterlambatan',
        'potongan',
        'alasan_pengecualian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }
}
