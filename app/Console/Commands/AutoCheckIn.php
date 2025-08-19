<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Punishment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FonnteMessageService;

class AutoCheckIn extends Command
{
    protected $signature = 'absensi:auto-checkin';
    protected $description = 'Generate check-in otomatis setiap pagi jam 08:45';

    public function handle()
    {
        $today = Carbon::today();
        $dayName = $today->format('l');

        if ($dayName === 'Sunday') {
            $this->info("Hari Minggu - Semua devisi libur, tidak ada auto check-in.");
            Log::info("Hari Minggu - Semua devisi libur, tidak ada auto check-in.");
            return;
        }

        $devisiAktif = $dayName === 'Saturday' ? ['Produksi 1', 'Produksi 2'] : [];

        $users = User::with('devisi')->get();

        foreach ($users as $user) {
            try {
                if ($user->role === 'superadmin') continue;
                if (!$user->devisi || empty($user->devisi->jam_mulai)) continue;
                if ($dayName === 'Saturday' && !in_array($user->devisi->nama, $devisiAktif)) continue;

                $sudahCheckIn = Absensi::where('user_id', $user->id)
                    ->whereDate('tanggal', $today)
                    ->where('jenis', 'check_in')
                    ->exists();
                if ($sudahCheckIn) continue;

                $jamMulai = Carbon::parse($user->devisi->jam_mulai);
                $jamSekarang = Carbon::now();
                $terlambatMenit = $jamSekarang->greaterThan($jamMulai)
                    ? $jamMulai->diffInMinutes($jamSekarang)
                    : 0;

                $kenaPotongan = false;

                DB::transaction(function () use ($user, $today, $jamSekarang, $terlambatMenit, &$kenaPotongan) {
                    // Create absensi
                    $absensi = Absensi::create([
                        'user_id' => $user->id,
                        'tanggal' => $today,
                        'jenis' => 'check_in',
                        'status_checkout' => 'user tidak check out',
                        'waktu_masuk' => $jamSekarang,
                        'waktu_keluar' => null,
                        'latitude' => null,
                        'longitude' => null,
                        'jangkauan_radius' => false,
                    ]);

                    // Jika terlambat, create punishment dan update gaji_total
                    if ($terlambatMenit > 0) {
                        $potongan = $user->potongan_keterlambatan ?? 0;

                        Punishment::create([
                            'absensi_id' => $absensi->id,
                            'user_id' => $user->id,
                            'tanggal' => $today,
                            'potongan' => $potongan,
                        ]);

                        // Kurangi gaji_total langsung
                        $user->gaji_total -= $potongan;
                        $user->save();

                        $kenaPotongan = true;
                    }
                });

                // Kirim WA
                try {
                    $formattedDate = Carbon::parse($today, 'Asia/Jakarta')->translatedFormat('d-m-Y');
                    $formattedTime = $jamSekarang->format('H:i');

                    $pesanNotifikasi = "*" . $user->nama_lengkap . "* hadir pada "
                        . $formattedDate . " " . $formattedTime;

                    if ($kenaPotongan) {
                        $pesanNotifikasi .= ". *Late Present*";
                    }

                    $pesanNotifikasi .= "\n_(Absensi by Sistem)_";

                    app(FonnteMessageService::class)->sendToGroup($pesanNotifikasi);
                } catch (\Throwable $e) {
                    Log::error('Gagal kirim WA auto check-in: ' . $e->getMessage(), ['user_id' => $user->id]);
                }

            } catch (\Exception $e) {
                $this->error("Gagal auto check-in user {$user->name}: {$e->getMessage()}");
                Log::error("Gagal auto check-in user {$user->name}", [
                    'user_id' => $user->id,
                    'tanggal' => $today->toDateString(),
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
