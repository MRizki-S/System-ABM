<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Absensi;
use App\Models\Punishment;
use App\Models\JamKerjaDevisi;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'role',
        'gaji_pokok',
        'gaji_total',
        'potongan_keterlambatan',
        'devisi_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // relasi

    public function devisi()
    {
        return $this->belongsTo(JamKerjaDevisi::class, 'devisi_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function punishments()
    {
        return $this->hasMany(Punishment::class);
    }
}
