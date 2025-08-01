<?php

namespace App\Http\Controllers;

use App\Models\Punishment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Punishment::with(['absensi', 'user.devisi']);

        $selectedDate = null;

        if ($request->has('tanggal_filter') && !empty($request->tanggal_filter)) {
            $filterDate = Carbon::parse($request->tanggal_filter)->toDateString();

            $query->whereHas('absensi', function ($q) use ($filterDate) {
                $q->whereDate('tanggal', $filterDate);
            });

            $selectedDate = $request->tanggal_filter;
        } else {
            $bulanNow = Carbon::now()->startOfMonth(); // ambil awal bulan ini

            // $startOfMonth = $bulanNow->copy()->subMonth()->day(26); // 26 bulan sebelumnya
            $startOfMonth = $bulanNow->copy()->startOfDay(); // start bulan ini
            $endOfMonth = $bulanNow->copy()->endOfMonth()->endOfDay(); // 25 bulan sekarang

            $query->whereHas('absensi', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
            });
        }

        // Filter berdasarkan user jika bukan superadmin atau hrd
        if (Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'hrd') {
            $query->where('user_id', auth()->id());
        }

        $dataPelanggaran = $query->orderBy('created_at', 'desc')->get();

        return view('Pelanggaran.indexPelanggaran', compact('dataPelanggaran', 'selectedDate'));
    }


    public function destroy($id)
    {
        $pelanggaran = Punishment::with('user')->findOrFail($id);
        $user = $pelanggaran->user;
        // dd($user);
        // Cek jika gaji_total lebih besar dari gaji_pokok (sudah di-reset tapi ingin hapus pelanggaran lama)
        if ($user->gaji_total >= $user->gaji_pokok) {
            Session::flash('error', 'Tidak bisa menghapus pelanggaran ini. Hubungi staff IT. Pelanggaran ini berkaitan dengan potongan gaji yang sudah direset ke default.');
            return redirect()->back();
        }

        // Ambil nilai potongan
        $potongan = $pelanggaran->potongan ?? 0;

        if ($potongan > 0) {
            // Tambahkan kembali ke gaji total user
            $user = $pelanggaran->user;
            $user->gaji_total += $potongan;
            $user->save();
        }

        // Hapus pelanggaran
        $deleteDataPelanggaran = $pelanggaran->delete();

        if (!$deleteDataPelanggaran) {
            Session::flash('error', 'Oops! 😓 Ada yang salah saat menghapus data pelanggaran. Coba lagi sebentar, ya!');
            return redirect()->back();
        }
        Session::flash('success', 'Pelanggaran tersebut berhasil dihapus, dan dana potongan berhasil dikembalikan!');
        return redirect()->back();
    }
}
