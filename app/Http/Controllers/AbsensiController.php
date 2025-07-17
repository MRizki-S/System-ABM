<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Punishment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\FonnteMessageService;
use Illuminate\Support\Facades\Session;

class AbsensiController extends Controller
{

    // Deklarasikan properti untuk service
    protected FonnteMessageService $fonnteMessageService;

    // Gunakan constructor injection
    public function __construct(FonnteMessageService $fonnteMessageService)
    {
        $this->fonnteMessageService = $fonnteMessageService;
    }

    // Hitung jangkauan radius dalam meter 50 meter
    private function hitungJarakMeter($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    // hitung potongan keterlambatan
    private function hitungPotonganKeterlambatan($waktuMasuk, $jamKerjaMulai, $potonganMaksimal)
    {
        $masuk = Carbon::parse($waktuMasuk);
        $mulai = Carbon::parse($jamKerjaMulai);

        $menitTerlambat = $mulai->diffInMinutes($masuk);

        if ($menitTerlambat <= 0) {
            return 0;
        }

        if ($menitTerlambat >= 46) {
            $persen = 1.0; // 100%
        } elseif ($menitTerlambat >= 16) {
            $persen = 0.5; // 50%
        } else {
            $persen = 0.15; // 15%
        }

        return floor($potonganMaksimal * $persen);
    }


    public function checkInView()
    {
        // return view('Absensi.checkin');
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        $sudahCheckIn = Absensi::where('user_id', $userId)
            ->where('tanggal', $today)
            ->whereIn('jenis', ['check_in', 'check_out', 'sakit', 'izin']) // Cek jenis absensi
            ->first();

        // dd($sudahCheckIn);
        return view('Absensi.checkin', [
            'sudahCheckIn' => $sudahCheckIn
        ]);
    }
    public function checkInAksi(Request $request)
    {

        $request->validate([
            'tanggal' => 'required|date',
            'waktu_masuk' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // ambil user yang sedang login
        $user = Auth::user();

        // Lokasi kantor
        $kantorLat = -8.13915542278884;
        $kantorLong = 113.70078301842943;

        // Lokasi user saat ini
        $userLat = $request->latitude;
        $userLong = $request->longitude;

        // Hitung jarak dari kantor (dalam meter)
        $radius = $this->hitungJarakMeter($kantorLat, $kantorLong, $userLat, $userLong);
        // dd($radius);

        // Validasi: tidak boleh lebih dari 50 meter
        if ($radius > 50) {
            Session::flash('error', 'Oops! Lokasi Anda terlalu jauh dari kantor. Jarak Anda: ' . round($radius, 2) . ' meter.');
            return redirect()->back();
        }


        // Simpan absensi
        $absensiCheckin = Absensi::create([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'jenis' => 'check_in',
            'keterangan' => null,
            'status_checkout' => 'user tidak check out',
            'waktu_masuk' => $request->waktu_masuk,
            'waktu_keluar' => null,
            'latitude' => $userLat,
            'longitude' => $userLong,
            'jangkauan_radius' => round($radius, 2), // simpan jaraknya saja
        ]);

        if (!$absensiCheckin) {
            Session::flash('error', 'Oops! Terjadi kesalahan saat menyimpan data check-in. Silakan coba lagi. hubungi staff it jika masalah berlanjut.');
            return redirect()->back();
        }

        // validasi keterlambatan
        // Simulasi input
        $waktuMasuk = $request->input('waktu_masuk'); // '08:10'
        $jamKerjaMulai = $user->devisi->jam_mulai;
        $potonganMaksimal = $user->potongan_keterlambatan;
        // dd($waktuMasuk, $jamKerjaMulai, $potonganMaksimal);

        // Hitung potongan lewat private method
        $potongan = $this->hitungPotonganKeterlambatan($waktuMasuk, $jamKerjaMulai, $potonganMaksimal);
        // dd($potongan);

        // Simpan punishment jika perlu
        if ($potongan > 0) {
            Punishment::create([
                'user_id' => $user->id,
                'absensi_id' => $absensiCheckin->id, // contoh
                'jam_keterlambatan' => $waktuMasuk,
                'potongan' => $potongan,
            ]);

            $user->gaji_total -= $potongan;
            $user->save();
        }

        // --- Bagian PENTING: Kirim Pesan ke Grup WhatsApp ---
        try {
            // Menggunakan Carbon untuk format tanggal dan waktu yang rapi
            $formattedDate = Carbon::parse($absensiCheckin->tanggal)->translatedFormat('d-m-Y'); // Contoh: 30-06-2025
            $formattedTime = Carbon::parse($absensiCheckin->waktu_masuk)->format('H:i'); // Contoh: 08:30

            // Simple check-in notification message, without extra lines or details
            $pesanNotifikasi = "*" . $user->nama_lengkap . "* hadir pada " . $formattedDate . " " . $formattedTime . ".";

            // Panggil service untuk mengirim pesan
            $this->fonnteMessageService->sendToGroup($pesanNotifikasi);
        } catch (\Throwable $e) {
            Log::error('Terjadi kesalahan saat mengirim notifikasi WhatsApp setelah check-in: ' . $e->getMessage());
            // Log error, tapi jangan menghentikan proses check-in utama
        }
        // --- Akhir Bagian WhatsApp ---


        Session::flash('success', 'Check-in berhasil!');
        return redirect('/absensi/check-in');
    }

    public function checkOutView()
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        $sudahCheckOut = Absensi::where('user_id', $userId)
            ->where('tanggal', $today)
            ->whereNotNull('waktu_keluar') // Sudah checkout
            ->first();

        return view('Absensi.checkout', compact('sudahCheckOut'));
    }
    public function checkOutAksi(Request $request)
    {
        $request->validate([
            'waktu_keluar' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        // dd($user->devisi->jam_selesai);
        $today = Carbon::today()->toDateString();
        $waktuKeluar = Carbon::parse($request->waktu_keluar);
        $jamSelesai = Carbon::parse($user->devisi->jam_selesai);

        if ($waktuKeluar->lt($jamSelesai)) {
            return redirect()->back()->with('error', 'Waktu check-out tidak boleh sebelum jam selesai kerja (' . $jamSelesai->format('H:i') . ').');
        }

        $absensi = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->where('jenis', 'check_in')
            ->first();

        if (!$absensi) {
            return redirect()->back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        // Lokasi kantor
        $kantorLat = -8.13915542278884;
        $kantorLong = 113.70078301842943;

        // Lokasi user saat ini
        $userLat = $request->latitude;
        $userLong = $request->longitude;

        // Hitung jarak dari kantor (dalam meter)
        $radius = $this->hitungJarakMeter($kantorLat, $kantorLong, $userLat, $userLong);

        // Validasi: tidak boleh lebih dari 50 meter
        if ($radius > 50) {
            Session::flash('error', 'Oops! Lokasi Anda terlalu jauh dari kantor. Jarak Anda: ' . round($radius, 2) . ' meter.');
            return redirect()->back();
        }

        $absensiCheckout = $absensi->update([
            'waktu_keluar'     => $request->waktu_keluar,
            'jenis' => 'check_out',
            'status_checkout'  => 'user check out',
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'jangkauan_radius' => $radius <= 50 ? true : false, // simpan status jangkauan radius
        ]);
        if (!$absensiCheckout) {
            Session::flash('error', 'Oops! Terjadi kesalahan saat menyimpan data check-out. Silakan coba lagi. hubungi staff it jika masalah berlanjut.');
            return redirect()->back();
        }

        // --- Bagian PENTING: Kirim Pesan ke Grup WhatsApp ---
        try {
            // Pastikan user memiliki properti 'nama_lengkap'
            $userName = $user->nama_lengkap;
            $checkoutTime = Carbon::parse($request->waktu_keluar)->format('H:i');

            // Pesan notifikasi untuk check-out
            $pesanNotifikasi = "Terima kasih *" . $userName . "* sudah bekerja secara profesional hingga pukul " . $checkoutTime . ".";

            // Panggil service untuk mengirim pesan
            $this->fonnteMessageService->sendToGroup($pesanNotifikasi);
        } catch (\Throwable $e) {
            Log::error('Terjadi kesalahan saat mengirim notifikasi WhatsApp setelah check-out: ' . $e->getMessage());
            // Log error, tapi jangan menghentikan proses check-out utama
        }
        // --- Akhir Bagian WhatsApp ---

        Session::flash('success', 'Check-in berhasil!');
        return redirect('/absensi/check-out');
    }

    public function absenView()
    {
        $userId = Auth::id();
        $today = Carbon::now()->toDateString();

        // Cek apakah user sudah absen hari ini (jenis check_in atau izin/sakit)
        $sudahAbsenHariIni = Absensi::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->whereIn('jenis', ['sakit', 'izin']) // Hanya ambil jenis sakit atau izin
            ->first();

        // dd($sudahAbsenHariIni);
        return view('Absensi.absen', compact('sudahAbsenHariIni'));
    }
    public function absenAksi(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:sakit,izin',
            'keterangan' => 'required|string|max:40',
        ]);

        $absensiAbsen = Absensi::create([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'status_checkout' => 'user tidak check out',
            'waktu_masuk' => null,
            'waktu_keluar' => null,
            'latitude' => null, // Tidak diperlukan untuk jenis absen ini
            'longitude' => null, // Tidak diperlukan untuk jenis absen ini
            'jangkauan_radius' => false, // Tidak diperlukan untuk jenis absen ini
        ]);

        if (!$absensiAbsen) {
            Session::flash('error', 'Oops! Terjadi kesalahan saat menyimpan data absensi. Silakan coba lagi. hubungi staff it jika masalah berlanjut.');
            return redirect()->back();
        }

        // --- Bagian PENTING: Kirim Pesan ke Grup WhatsApp ---
        try {
            $user = Auth::user();
            $userName = $user->nama_lengkap; // Ambil nama lengkap user
            $formattedDate = Carbon::parse($request->tanggal)->translatedFormat('d-m-Y'); // Format tanggal: 30-06-2025
            $jenisAbsen = ucfirst($request->jenis); // Sakit atau Izin
            $keterangan = $request->keterangan;

            $pesanNotifikasi = "";

            // pesan notifikasi untuk sakit dan izin
            $pesanNotifikasi .= "*" . $userName . "* tidak dapat hadir dikarenakan *" . $jenisAbsen . "* pada tanggal " . $formattedDate . ".";
            $pesanNotifikasi .= "\n*Keterangan:* " . $keterangan;

            // Panggil service untuk mengirim pesan
            if (!empty($pesanNotifikasi)) { // Pastikan pesan tidak kosong
                $this->fonnteMessageService->sendToGroup($pesanNotifikasi);
            }
        } catch (\Throwable $e) {
            Log::error('Terjadi kesalahan saat mengirim notifikasi WhatsApp setelah absen: ' . $e->getMessage());
            // Log error, tapi jangan menghentikan proses absen utama
        }
        // --- Akhir Bagian WhatsApp ---

        Session::flash('success', 'Absen ketidakhadiran dengan alasan ' . ucfirst($request->jenis) . ' berhasil dicatat!');
        return redirect('/absensi/absen');
    }
}
