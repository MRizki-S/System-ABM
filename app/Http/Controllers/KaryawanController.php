<?php
namespace App\Http\Controllers;

use App\Models\JamKerjaDevisi;
use App\Models\Perumahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataKaryawan = User::all();

        // $search = $request->query('search');
        // $filterDevisi = $request->query('devisi');

        // $dataKaryawan = User::with('devisi')
        //     ->when($search, function ($query, $search) {
        //         $query->where('username', 'like', "%{$search}%")
        //             ->orWhere('nama_lengkap', 'like', "%{$search}%");
        //     })
        //     ->when($filterDevisi, function ($query, $filterDevisi) {
        //         $query->whereHas('devisi', function ($q) use ($filterDevisi) {
        //             $q->where('nama_devisi', $filterDevisi);
        //         });
        //     })
        //     ->paginate(4);

        return view('Employees.indexKaryawan', compact('dataKaryawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devisi     = JamKerjaDevisi::all();
        $perumahaan = Perumahaan::all();
        return view('Employees.createKaryawan', compact('devisi', 'perumahaan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'               => 'required|string|max:255|unique:users,username',
            'password'               => 'required|min:8',
            'nama_lengkap'           => 'required|string|max:255',
            'devisi_id'              => 'required|exists:jam_kerja_devisi,id',
            'perumahaan_id'          => 'required|exists:perumahaan,id',
            'role'                   => 'required',
            'potongan_keterlambatan' => 'nullable|numeric|min:1',
            'gaji_pokok'             => 'nullable|numeric|min:1',
        ]);

        $request['gaji_total'] = $request->gaji_pokok;

        $createUser = User::create($request->all());

        if ($createUser) {
            Session::flash('success', 'Karyawan baru berhasil ditambahkan!');
            return redirect('/karyawan');
        } else {
            Session::flash('error', 'Oops! 😓 Ada yang salah saat menambahkan karyawan. Coba lagi sebentar, ya!');
            return redirect('/karyawan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        $detailKaryawan = User::with([
            'devisi',
            'punishments' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->with('absensi') // <<-- load tanggal dari absensi
                    ->whereHas('absensi', function ($subQuery) use ($startOfMonth, $endOfMonth) {
                        $subQuery->whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')]);
                    })
                    ->orderBy('created_at', 'desc');
            },
        ])->findOrFail($id);

        // dd($detailKaryawan);

        return view('Employees.detailKaryawan', compact('detailKaryawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $editKaryawan = User::findOrFail($id);
        $devisi       = JamKerjaDevisi::all();
        // dd($editKaryawan);
        return view('Employees.editKaryawan', compact('editKaryawan', 'devisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'username'               => 'required|string|max:255|unique:users,username,' . $id,
            'nama_lengkap'           => 'required|string|max:255',
            'devisi_id'              => 'required|exists:jam_kerja_devisi,id',
            'role'                   => 'required',
            'potongan_keterlambatan' => 'nullable|numeric|min:1',
            'gaji_pokok'             => 'nullable|numeric|min:1',
        ]);
                                                       // dd($request->all());
        $request['gaji_total'] = $request->gaji_pokok; // Set gaji_total to gaji_pokok by default

        $user       = User::findOrFail($id);
        $updateUser = $user->update($request->all());

        if (! $updateUser) {
            Session::flash('error', 'Oops! 😓 Ada yang salah saat memperbarui karyawan. Coba lagi sebentar, ya!');
            return redirect()->back();
        }

        Session::flash('success', 'Karyawan berhasil diperbarui!');
        return redirect('/karyawan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        // dd($user);
        $deleteUser = $user->delete();
        // dd($deleteUser);
        if (! $deleteUser) {
            Session::flash('error', 'Oops! 😓 Ada yang salah saat menghapus karyawan. Coba lagi sebentar, ya!');
            return redirect()->back();
        }
        Session::flash('success', 'Karyawan berhasil dihapus!');
        return redirect()->back();
    }

    // reset gaji akhir seluruh karyawan
    public function resetAllSalaries(Request $request)
    {
        try {
            // Ambil semua user dan update gaji_total mereka
            // Menggunakan chunk() untuk efisiensi pada banyak data user
            User::chunk(500, function ($users) {
                foreach ($users as $user) {
                    if (! is_null($user->gaji_pokok)) { // Pastikan gaji_pokok tidak kosong
                        $user->gaji_total = $user->gaji_pokok;
                        $user->save();
                    }
                }
            });

            // Catat aktivitas di log
            Log::info('Gaji total semua karyawan berhasil direset ke gaji pokok oleh ' . auth()->user()->name);

            // Beri notifikasi sukses ke user
            Session::flash('success', 'Gaji total semua karyawan berhasil direset ke gaji pokok!');
            return redirect()->back();
        } catch (\Throwable $e) {
            // Catat error jika terjadi masalah
            Log::error('Gagal mereset gaji total semua karyawan: ' . $e->getMessage(), [
                'user_id' => auth()->id() ?? 'Guest', // Tangani jika user tidak terautentikasi
                'trace'   => $e->getTraceAsString(),
            ]);

            // Beri notifikasi error ke user
            Session::flash('error', 'Terjadi kesalahan saat mereset gaji: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
