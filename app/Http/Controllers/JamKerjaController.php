<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JamKerjaDevisi;
use Illuminate\Support\Facades\Session;

class JamKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataJamKerja = JamKerjaDevisi::all();
        return view('JamKerja.indexJamKerja', compact('dataJamKerja'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_devisi' => 'required|string|max:255',
            'nama_jamkerja' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $createJamKerja = JamKerjaDevisi::create($request->all());
        if($createJamKerja) {
            Session::flash('success', 'Jam Kerja baru berhasil ditambahkan!');
            return redirect('/jam-kerja');
        } else {
             Session::flash('error',  'Oops! 😓 Ada yang salah saat menambahkan jam kerja. Coba lagi sebentar, ya!');
            return redirect('/jam-kerja');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'nama_devisi' => 'required|string|max:255',
            'nama_jamkerja' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jamKerja = JamKerjaDevisi::findOrFail($id);
        $updateJamKerja = $jamKerja->update($request->all());

        if ($updateJamKerja) {
            Session::flash('success', 'Jam Kerja berhasil diperbarui!');
            return redirect('/jam-kerja');
        } else {
            Session::flash('error',  'Oops! 😓 Ada yang salah saat memperbarui jam kerja. Coba lagi sebentar, ya!');
            return redirect('/jam-kerja');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jamKerja = JamKerjaDevisi::findOrFail($id);
        $deleteJamKerja = $jamKerja->delete();

        if ($deleteJamKerja) {
            Session::flash('success', 'Jam Kerja berhasil dihapus!');
            return redirect('/jam-kerja');
        } else {
            Session::flash('error',  'Oops! 😓 Ada yang salah saat menghapus jam kerja. Coba lagi sebentar, ya!');
            return redirect('/jam-kerja');
        }
    }
}
