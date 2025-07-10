<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = User::with('devisi')->find(Auth::id());

        // Return the profile view with user data
        return view('Profile.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        // dd($request->all());
        
        // Validate the request data
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'nama_lengkap' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => [
                'nullable',
                'required_with:password', // jika password diisi, konfirmasi wajib
            ],
        ]);

        // Update user information
        $user->username = $request->input('username');
        $user->nama_lengkap = $request->input('nama_lengkap');

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $updateProfile = $user->save();

        if (!$updateProfile) {
            Session::flash('error', 'Oops! 😓 Ada yang salah saat memperbarui profile anda. Coba lagi sebentar, ya!');
            return redirect()->back();
        }

        Session::flash('success', 'Profile anda berhasil diperbarui!');
        return redirect()->back();
    }
}
