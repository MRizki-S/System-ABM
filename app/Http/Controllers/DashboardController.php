<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Punishment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function DashboardKaryawan()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $bulanIni = Carbon::now()->startOfMonth();
        $akhirBulan = Carbon::now()->endOfMonth();

        // Ambil jam masuk hari ini
        $absenHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();
        $jamMasukHariIni = $absenHariIni->waktu_masuk ?? '-';

        // Hadir bulan ini (jenis selain izin/sakit)
        $totalHadir = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$bulanIni, $akhirBulan])
            ->whereNotIn('jenis', ['izin', 'sakit'])
            ->count();

        // Total sakit bulan ini
        $totalSakit = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$bulanIni, $akhirBulan])
            ->where('jenis', 'sakit')
            ->count();

        // Total izin bulan ini
        $totalIzin = Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$bulanIni, $akhirBulan])
            ->where('jenis', 'izin')
            ->count();

        // Total terlambat (punishment) bulan ini
        $totalTerlambat = Punishment::where('user_id', $user->id)
            ->whereHas('absensi', function ($query) use ($bulanIni, $akhirBulan) {
                $query->whereBetween('tanggal', [$bulanIni, $akhirBulan]);
            })
            ->count();


        $bulanNow = Carbon::now()->startOfMonth(); // ambil awal bulan ini

        $startOfMonth = $bulanNow->copy()->subMonth()->day(26); // 26 bulan sebelumnya
        $endOfMonth = $bulanNow->copy()->day(25);

        // Query absensi user login hanya untuk bulan ini
        $dataRekapAbsensi = Absensi::with(['punishment', 'user.devisi'])
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal', 'desc')
            ->get();
        // dd($dataRekapAbsensi);

        return view('Dashboard.Dashboard', [
            'user' => $user,
            'jamMasukHariIni' => $jamMasukHariIni,
            'totalHadir' => $totalHadir,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'totalTerlambat' => $totalTerlambat,
            'bulanIni' => now()->locale('id')->translatedFormat('F Y'),
            'dataRekapAbsensi' => $dataRekapAbsensi
        ]);
    }
}
