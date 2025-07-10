<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\JamKerjaDevisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seeder untuk jam_kerja_devisi
        $devisi = JamKerjaDevisi::create([
            'nama_devisi' => 'operasional',
            'nama_jamkerja' => 'Office',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '17:00:00',
        ]);

        // 2. Seeder untuk users
        $users = [
            [
                'username' => 'admin45',
                'password' => Hash::make('rahasia45'),
                'nama_lengkap' => 'admin45',
                'role' => 'superadmin',
                'gaji_pokok' => 0,
                'gaji_total' => 0,
                'devisi_id' => $devisi->id,
            ],
            [
                'username' => 'bagas',
                'password' => Hash::make('bagas4545'),
                'nama_lengkap' => 'AGUSTYAN KRISNA BAGASKARA',
                'role' => 'hrd',
                'gaji_pokok' => 0,
                'gaji_total' => 0,
                'devisi_id' => $devisi->id,
            ],
            [
                'username' => 'fina',
                'password' => Hash::make('fina1245'),
                'nama_lengkap' => 'FINA ATIKA NURMA R',
                'role' => 'hrd',
                'gaji_pokok' => 0,
                'gaji_total' => 0,
                'devisi_id' => $devisi->id,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
