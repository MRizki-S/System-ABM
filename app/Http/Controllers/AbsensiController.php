<?php
namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Punishment;
use App\Services\FonnteMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $dLat        = deg2rad($lat2 - $lat1);
        $dLon        = deg2rad($lon2 - $lon1);

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
        } elseif ($menitTerlambat >= 26) {
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
        $today  = Carbon::today()->toDateString();

        $sudahCheckIn = Absensi::where('user_id', $userId)
            ->where('tanggal', $today)
            ->whereIn('jenis', ['check_in', 'check_out', 'sakit', 'izin']) // Cek jenis absensi
            ->first();

        // dd($sudahCheckIn);
        return view('Absensi.checkin', [
            'sudahCheckIn' => $sudahCheckIn,
        ]);
    }
    public function checkInAksi(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'waktu_masuk' => 'required',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        // user login
        $user       = Auth::user();
        $perumahaan = $user->perumahaan;

        if (! $perumahaan) {
            Session::flash('error', 'Perumahaan tidak ditemukan. Hubungi staff IT.');
            return redirect()->back();
        }

        // ambil data perumahaan (lokasi + radius)
        $kantorLat     = $perumahaan->latitude;
        $kantorLong    = $perumahaan->longitude;
        $radiusAllowed = $perumahaan->radius ?? 50; // default 50 meter

        // Lokasi user saat ini
        $userLat  = $request->latitude;
        $userLong = $request->longitude;

        // Hitung jarak user dari kantor
        $radius = $this->hitungJarakMeter($kantorLat, $kantorLong, $userLat, $userLong);

        // Validasi jangkauan
        if ($radius > $radiusAllowed) {
            Session::flash('error', 'Oops! Lokasi Anda terlalu jauh dari kantor. Jarak Anda: ' . round($radius, 2) . ' meter.');
            return redirect()->back();
        }

        // Simpan absensi check-in
        $absensiCheckin = Absensi::create([
            'user_id'          => $user->id,
            'perumahaan_id'    => $perumahaan->id,
            'tanggal'          => $request->tanggal,
            'jenis'            => 'check_in',
            'keterangan'       => null,
            'status_checkout'  => 'user tidak check out',
            'waktu_masuk'      => $request->waktu_masuk,
            'waktu_keluar'     => null,
            'latitude'         => $userLat,
            'longitude'        => $userLong,
            'jangkauan_radius' => round($radius, 2),
        ]);

        if (! $absensiCheckin) {
            Session::flash('error', 'Oops! Terjadi kesalahan saat menyimpan data check-in. Silakan coba lagi.');
            return redirect()->back();
        }

        // validasi keterlambatan
        $waktuMasuk       = $request->input('waktu_masuk');
        $jamKerjaMulai    = $user->devisi->jam_mulai;
        $potonganMaksimal = $user->potongan_keterlambatan;

        $potongan = $this->hitungPotonganKeterlambatan($waktuMasuk, $jamKerjaMulai, $potonganMaksimal);

        if ($potongan > 0) {
            Punishment::create([
                'user_id'           => $user->id,
                'absensi_id'        => $absensiCheckin->id,
                'jam_keterlambatan' => $waktuMasuk,
                'potongan'          => $potongan,
            ]);

            $user->gaji_total -= $potongan;
            $user->save();
        }

        // Kirim notifikasi WhatsApp ke group perumahaan
        try {
            $formattedDate = Carbon::parse($absensiCheckin->tanggal)->translatedFormat('d-m-Y');
            $formattedTime = Carbon::parse($absensiCheckin->waktu_masuk)->format('H:i');

            $pesanNotifikasi = "*" . $user->nama_lengkap . "* hadir pada " . $formattedDate . " " . $formattedTime . ".";

            // buat service dengan group_id dari perumahaan
            $fonnteService = new FonnteMessageService();
            $fonnteService->sendToGroup($perumahaan->wa_group_id, $pesanNotifikasi);

        } catch (\Throwable $e) {
            Log::error('Gagal kirim WA setelah check-in: ' . $e->getMessage());
        }

        Session::flash('success', 'Check-in berhasil!');
        return redirect('/absensi/check-in');
    }

    public function checkOutView()
    {
        $userId = Auth::id();
        $today  = Carbon::today()->toDateString();

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
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
        ]);

        $user        = Auth::user();
        $today       = Carbon::today()->toDateString();
        $waktuKeluar = Carbon::parse($request->waktu_keluar);
        $jamSelesai  = Carbon::parse($user->devisi->jam_selesai);

        if ($waktuKeluar->lt($jamSelesai)) {
            return redirect()->back()->with('error', 'Waktu check-out tidak boleh sebelum jam selesai kerja (' . $jamSelesai->format('H:i') . ').');
        }

        $absensi = Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->where('jenis', 'check_in')
            ->first();

        if (! $absensi) {
            return redirect()->back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

                                     // Ambil koordinat kantor & group WA dari tabel perumahaan
        $kantor = $user->perumahaan; // pastikan relasi perumahaan() ada di model User
        if (! $kantor) {
            return redirect()->back()->with('error', 'Perumahaan tidak ditemukan untuk user ini.');
        }

        $kantorLat  = $kantor->latitude;
        $kantorLong = $kantor->longitude;
        $groupWaId  = $kantor->wa_group_id; // pastikan kolom ini ada di tabel perumahaan

        // Lokasi user saat ini
        $userLat  = $request->latitude;
        $userLong = $request->longitude;

        // Hitung jarak dari kantor
        $radius = $this->hitungJarakMeter($kantorLat, $kantorLong, $userLat, $userLong);

        if ($radius > 50) {
            Session::flash('error', 'Oops! Lokasi Anda terlalu jauh dari kantor. Jarak Anda: ' . round($radius, 2) . ' meter.');
            return redirect()->back();
        }

        $absensiCheckout = $absensi->update([
            'waktu_keluar'     => $request->waktu_keluar,
            'jenis'            => 'check_out',
            'status_checkout'  => 'user check out',
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'jangkauan_radius' => $radius <= 50 ? true : false,
        ]);

        if (! $absensiCheckout) {
            Session::flash('error', 'Oops! Terjadi kesalahan saat menyimpan data check-out. Silakan coba lagi.');
            return redirect()->back();
        }

        // --- Kirim WA ---
        try {
            $userName     = $user->nama_lengkap;
            $checkoutTime = Carbon::parse($request->waktu_keluar)->format('H:i');

            $pesanNotifikasi = "Terima kasih *{$userName}* sudah bekerja secara profesional hingga pukul {$checkoutTime}.";

            $this->fonnteMessageService->sendToGroup($groupWaId, $pesanNotifikasi);
        } catch (\Throwable $e) {
            Log::error('Gagal kirim WA setelah check-out: ' . $e->getMessage());
        }

        Session::flash('success', 'Check-out berhasil!');
        return redirect('/absensi/check-out');
    }

    public function absenView()
    {
        $userId = Auth::id();
        $today  = Carbon::now()->toDateString();

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
            'tanggal'    => 'required|date',
            'jenis'      => 'required|in:sakit,izin',
            'keterangan' => 'required|string|max:40',
        ]);

        // Ambil waktu server untuk tanggal absensi
        $tanggalAbsen = Carbon::parse($request->tanggal)->toDateString();

        // Cek apakah user sudah punya absensi untuk tanggal ini
        $sudahAbsen = Absensi::where('user_id', Auth::id())
            ->where('tanggal', $tanggalAbsen)
            ->exists();

        if ($sudahAbsen) {
            Session::flash('error', 'Oops! Anda sudah melakukan absensi hari ini.');
            return redirect()->back();
        }
        $user       = Auth::user();
        $perumahaan = $user->perumahaan;

        $absensiAbsen = Absensi::create([
            'user_id'          => Auth::id(),
            'perumahaan_id'    => $perumahaan->id,
            'tanggal'          => $request->tanggal,
            'jenis'            => $request->jenis,
            'keterangan'       => $request->keterangan,
            'status_checkout'  => 'user tidak check out',
            'waktu_masuk'      => null,
            'waktu_keluar'     => null,
            'latitude'         => null,  // Tidak diperlukan untuk jenis absen ini
            'longitude'        => null,  // Tidak diperlukan untuk jenis absen ini
            'jangkauan_radius' => false, // Tidak diperlukan untuk jenis absen ini
        ]);

        if (! $absensiAbsen) {
            Session::flash('error', 'Oops! Terjadi kesalahan saat menyimpan data absensi. Silakan coba lagi. hubungi staff it jika masalah berlanjut.');
            return redirect()->back();
        }

        // --- Bagian PENTING: Kirim Pesan ke Grup WhatsApp ---
        try {
            $userName      = $user->nama_lengkap;                                         // Ambil nama lengkap user
            $formattedDate = Carbon::parse($request->tanggal)->translatedFormat('d-m-Y'); // Format tanggal: 30-06-2025
            $jenisAbsen    = ucfirst($request->jenis);                                    // Sakit atau Izin
            $keterangan    = $request->keterangan;

            $pesanNotifikasi = "";

            // pesan notifikasi untuk sakit dan izin
            $pesanNotifikasi .= "*" . $userName . "* tidak dapat hadir dikarenakan *" . $jenisAbsen . "* pada tanggal " . $formattedDate . ".";
            $pesanNotifikasi .= "\n*Keterangan:* " . $keterangan;

                                            // Panggil service untuk mengirim pesan
            if (! empty($pesanNotifikasi)) { // Pastikan pesan tidak kosong
                $this->fonnteMessageService->sendToGroup($user->perumahaan->wa_group_id, $pesanNotifikasi);
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
