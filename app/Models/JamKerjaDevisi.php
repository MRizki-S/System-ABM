<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JamKerjaDevisi extends Model
{
    protected $table = 'jam_kerja_devisi';

    protected $fillable = [
        'nama_devisi',
        'nama_jamkerja',
        'jam_mulai',
        'jam_selesai',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'devisi_id');
    }
}

