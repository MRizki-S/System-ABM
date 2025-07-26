<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Punishment;
use Illuminate\Http\Request;
use App\Exports\AbsensiExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query Absensi dengan eager loading relasi yang dibutuhkan
        $query = Absensi::with(['punishment', 'user.devisi']);

        // Inisialisasi default variabel
        $selectedDate = null;
        $namaBulan = null;
        $startOfMonth = null;
        $endOfMonth = null;

        if ($request->has('tanggal_filter') && !empty($request->tanggal_filter)) {
            // Jika filter tanggal ada, gunakan tanggal tersebut
            $filterDate = Carbon::parse($request->tanggal_filter)->toDateString();
            $query->whereDate('tanggal', $filterDate);
            $selectedDate = $request->tanggal_filter;

            // Tentukan nama bulan dari tanggal yang difilter
            $namaBulan = Carbon::parse($filterDate)->locale('id')->translatedFormat('F Y');
        } else {
            // Dapatkan tanggal hari ini
            $today = Carbon::now();

            // if ($today->day <= 25) {
                // Jika masih tanggal 25 ke bawah: ambil dari 26 bulan sebelumnya sampai 25 bulan ini
                $startOfMonth = $today->copy()->subMonth()->day(1);
                $endOfMonth = $today->copy()->day(30);
                $namaBulan = $today->locale('id')->translatedFormat('F Y');
            // } else {
            //     // Jika sudah lewat tanggal 25: ambil dari 26 bulan ini sampai 25 bulan depan
            //     $startOfMonth = $today->copy()->day(26);
            //     $endOfMonth = $today->copy()->addMonth()->day(25);

            //     $namaBulan = $today->copy()->addMonth()->locale('id')->translatedFormat('F Y');
            // }


            // dd($startOfMonth->toDateString(), $endOfMonth->toDateString(), $namaBulan);
            // Terapkan filter range tanggal
            $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
        }

        $dataRekapAbsensi = $query->orderBy('created_at', 'desc')->get();
        // Urutkan data berdasarkan tanggal terbaru (descending)
        // dd($dataRekapAbsensi);

        return view('RekapAbsensi.indexRekapAbsensi', compact(
            'dataRekapAbsensi',
            'selectedDate',
            'namaBulan',
            'startOfMonth',
            'endOfMonth'
        ));
    }


    // expor to excel
    public function exportExcel(Request $request)
    {
        // Validasi input sesuai kategori
        $request->validate([
            'category' => 'required|in:hari,bulan',
            'tanggalHari' => 'nullable|required_if:category,hari|date',
            'bulan' => 'nullable|required_if:category,bulan|date_format:Y-m',
        ]);

        $category = $request->input('category');
        $filename = '';
        $data = [];
        $view = '';

        if ($category === 'hari') {
            $tanggal = Carbon::parse($request->tanggalHari)->format('Y-m-d');
            $carbonTanggal = Carbon::parse($tanggal);
            $tanggalList = [$tanggal];

            $users = User::with('devisi')->get();
            $rekap = [];

            foreach ($users as $user) {
                $data = Absensi::with('punishment')
                    ->where('user_id', $user->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();

                $hadir = $izin = $sakit = $potongan = 0;
                $absen = '-';

                if ($data) {
                    if ($data->jenis === 'izin') {
                        $izin++;
                        $absen = 'Izin - ' . ($data->keterangan ?? '');
                    } elseif ($data->jenis === 'sakit') {
                        $sakit++;
                        $absen = 'Sakit - ' . ($data->keterangan ?? '');
                    } else {
                        $hadir++;
                        $absen = ($data->waktu_masuk ?? '-') . ' - ' . ($data->waktu_keluar ?? '-');

                        if ($data->status_checkout) {
                            $absen .= ' (' . $data->status_checkout . ')';
                        }
                    }

                    if ($data->punishment && $data->punishment->potongan > 0) {
                        $potongan = $data->punishment->potongan;
                        $absen .= ' - Terlambat';
                    }
                }

                $rekap[] = [
                    'nama' => $user->nama_lengkap,
                    'divisi' => $user->devisi->nama_devisi ?? '-',
                    'absensi' => [$tanggal => $absen],
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'potongan' => (int) $potongan,
                ];
            }
            // dd($rekap);

            $filename = 'rekap-harian-' . $tanggal . '.xlsx';
            $view = 'export.rekap-harian';

            return Excel::download(new AbsensiExport([
                'rekap' => $rekap,
                'tanggalList' => $tanggalList,
                'tanggal' => $carbonTanggal, // for display
            ], $view), $filename);
        } elseif ($category === 'bulan') {
            $bulan = Carbon::createFromFormat('Y-m', $request->bulan);
            $start = $bulan->copy()->subMonth()->day(26); // 26 bulan sebelumnya
            $end = $bulan->copy()->day(25); // 25 bulan yg dipilih

            // Buat daftar tanggal sebulan
            $tanggalList = [];
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $tanggalList[] = $date->format('Y-m-d');
            }

            $users = User::with('devisi')->get();
            $rekap = [];

            foreach ($users as $user) {
                $absensi = Absensi::with('punishment')
                    ->where('user_id', $user->id)
                    ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->get();

                $absenPerTanggal = [];
                $hadir = $izin = $sakit = 0;

                foreach ($tanggalList as $tgl) {
                    $data = $absensi->first(function ($absen) use ($tgl) {
                        return $absen->tanggal->format('Y-m-d') === $tgl;
                    });

                    if ($data) {
                        // Hitung hadir, izin, sakit
                        if ($data->jenis === 'izin') {
                            $izin++;
                        } elseif ($data->jenis === 'sakit') {
                            $sakit++;
                        } else {
                            $hadir++;
                        }

                        // Ambil data waktu masuk & keluar
                        $jamMasuk = $data->waktu_masuk ?? '';
                        $jamKeluar = $data->waktu_keluar ?? '';

                        // Status tambahan: izin / sakit / check-out
                        $status = '';
                        if ($data->jenis === 'izin') {
                            $status = 'Izin - ' . ($data->keterangan ?? '');
                        } elseif ($data->jenis === 'sakit') {
                            $status = 'Sakit - ' . ($data->keterangan ?? '');
                        } else {
                            $status = $data->status_checkout ?? '';
                        }

                        // Tambahkan keterangan jika terlambat
                        $terlambat = ($data->punishment && $data->punishment->potongan > 0) ? ' - Terlambat' : '';

                        // Format tampilan absen
                        if ($jamMasuk && !$jamKeluar) {
                            $absenPerTanggal[$tgl] = "$jamMasuk - ($status$terlambat)";
                        } elseif (!$jamMasuk && !$jamKeluar) {
                            $absenPerTanggal[$tgl] = "($status$terlambat)";
                        } else {
                            $absenPerTanggal[$tgl] = "$jamMasuk - $jamKeluar ($status$terlambat)";
                        }
                    } else {
                        $absenPerTanggal[$tgl] = '';
                    }
                }

                // Hitung total potongan
                $potongan = Punishment::where('user_id', $user->id)
                    ->whereHas('absensi', function ($query) use ($start, $end) {
                        $query->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                    })
                    ->sum('potongan');

                // Ambil gaji pokok dan gaji akhir
                $gajiPokok = $user->gaji_pokok;
                $gajiAkhir = $user->gaji_total;

                $rekap[] = [
                    'nama' => $user->nama_lengkap,
                    'divisi' => $user->devisi->nama_devisi ?? '-',
                    'absensi' => $absenPerTanggal,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'gaji_pokok' => (int) $gajiPokok,
                    'potongan' => (int) $potongan,
                    'gaji_akhir' => (int) $gajiAkhir,
                ];
            }
            // dd($rekap);

            $bulanTerpilih = Carbon::createFromFormat('Y-m', $request->bulan);
            $filename = 'rekap-bulanan-' . $bulanTerpilih->translatedFormat('F-Y') . '.xlsx';
            $view = 'export.rekap-bulanan';

            // dd($bulanTerpilih);

            return Excel::download(
                new AbsensiExport([
                    'rekap' => $rekap,
                    'bulan' => $bulanTerpilih,
                    'tanggalList' => $tanggalList,
                ], $view),
                $filename
            );
        }
    }
}
