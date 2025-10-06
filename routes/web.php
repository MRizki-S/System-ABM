<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JamKerjaController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\RekapAbsensiController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/aksiLogin', [AuthController::class, 'login'])->name('aksiLogin');

Route::get('/404', function () {
    return view('errors.404');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['role:superadmin,hrd'])->group(function () {
        // Menu karyawan dan Jam kerja hanya bisa diakses oleh superadmin
        Route::resource('/karyawan', KaryawanController::class);
        Route::post('/gaji/reset-all', [KaryawanController::class, 'resetAllSalaries'])
            ->name('gaji.reset_all');

        Route::resource('/jam-kerja', JamKerjaController::class);

        Route::get('/rekap-absensi/export', [RekapAbsensiController::class, 'exportExcel'])->name('rekap.export');
        Route::resource('/rekap-absensi', RekapAbsensiController::class);
    });

    // dashboard url
    Route::get('/', [DashboardController::class, 'DashboardKaryawan'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'showProfile']);
    Route::put('/profile/update', [ProfileController::class, 'updateProfile']);


    Route::prefix('absensi/')->group(function () {
        Route::get('/check-in', [AbsensiController::class, 'checkInView'])->name('absensi.checkinView');
        Route::post('/check-inAksi', [AbsensiController::class, 'checkInAksi'])->name('absensi.checkinAksi');
        Route::get('/check-out', [AbsensiController::class, 'checkOutView']);
        Route::post('/check-outAksi', [AbsensiController::class, 'checkOutAksi']);

        Route::get('/absen', [AbsensiController::class, 'absenView'])->name('absen');
        Route::post('/absenAksi', [AbsensiController::class, 'absenAksi']);
    });

    Route::resource('/pelanggaran', PelanggaranController::class);
});


// Route::get('/fonnte-get-group-id-manual', function () {
//     $apiKey = env('FONNTE_API_KEY');
//     $fetchGroupUrl = env('FONNTE_FETCH_GROUP_URL');
//     $getGroupUrl = env('FONNTE_GET_GROUP_URL');

//     // Validasi API Key
//     if (empty($apiKey)) {
//         Log::error('Fonnte API Key belum diatur di .env');
//         return response('Fonnte API Key belum diatur.', 500);
//     }

//     // --- Langkah 1: Panggil API fetch-group (Update Daftar Grup di Fonnte) ---
//     Log::info('Fonnte: Memulai pembaruan daftar grup WhatsApp...');
//     try {
//         $fetchResponse = Http::withHeaders([
//             'Authorization' => $apiKey,
//         ])->post($fetchGroupUrl);

//         if (!$fetchResponse->successful() || !$fetchResponse->json('status')) {
//             Log::error('Fonnte: Gagal memperbarui daftar grup WhatsApp (fetch-group).', [
//                 'status' => $fetchResponse->status(),
//                 'response' => $fetchResponse->json()
//             ]);
//             return response('Gagal memperbarui daftar grup di Fonnte. Cek log Laravel.', 500);
//         }
//         Log::info('Fonnte: Daftar grup WhatsApp berhasil diperbarui.');
//     } catch (\Exception $e) {
//         Log::error('Fonnte: Exception saat memanggil fetch-group API: ' . $e->getMessage());
//         return response('Terjadi kesalahan saat memperbarui grup: ' . $e->getMessage(), 500);
//     }

//     // Beri sedikit jeda (opsional, tapi bisa membantu memastikan Fonnte selesai memproses)
//     sleep(2); // Jeda 2 detik

//     // --- Langkah 2: Panggil API get-whatsapp-group (Ambil Daftar Grup) ---
//     Log::info('Fonnte: Mengambil daftar grup WhatsApp...');
//     try {
//         $getGroupsResponse = Http::withHeaders([
//             'Authorization' => $apiKey,
//         ])->post($getGroupUrl);

//         if ($getGroupsResponse->successful() && $getGroupsResponse->json('status')) {
//             $groups = $getGroupsResponse->json('data');
//             Log::info('Fonnte: Berhasil mengambil daftar grup WhatsApp.', $groups);

//             // --- Menampilkan hasil ke browser ---
//             $output = '<html><head><title>Daftar ID Grup WhatsApp Fonnte</title>';
//             $output .= '<style>
//                 body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; color: #333; }
//                 .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
//                 h1 { color: #3498db; text-align: center; margin-bottom: 20px; }
//                 .message { background-color: #e8f5e9; border-left: 5px solid #4CAF50; margin-bottom: 20px; padding: 10px; border-radius: 4px; }
//                 ul { list-style: none; padding: 0; }
//                 li { background-color: #f9f9f9; border: 1px solid #ddd; margin-bottom: 10px; padding: 15px; border-radius: 5px; }
//                 strong { color: #555; }
//                 code { background-color: #eee; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
//                 .warning { background-color: #fff3cd; border-left: 5px solid #ffc107; color: #856404; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
//             </style>';
//             $output .= '</head><body><div class="container">';
//             $output .= '<h1>Daftar ID Grup WhatsApp Fonnte</h1>';
//             $output .= '<div class="warning">
//                 <p><strong>Peringatan Penting:</strong> URL ini memicu pembaruan daftar grup di Fonnte.
//                 Penggunaan berlebihan dapat menyebabkan nomor WhatsApp Anda diblokir.
//                 Gunakan HANYA jika Anda baru bergabung/keluar grup atau pertama kali mencari ID grup.</p>
//             </div>';


//             if (empty($groups)) {
//                 $output .= '<p>Tidak ada grup WhatsApp yang ditemukan. Pastikan nomor Fonnte Anda tergabung dalam grup.</p>';
//             } else {
//                 $output .= '<p class="message">Berikut adalah daftar grup WhatsApp Anda. Silakan catat ID grup yang Anda perlukan:</p>';
//                 $output .= '<ul>';
//                 foreach ($groups as $group) {
//                     $output .= '<li><strong>Nama Grup:</strong> ' . htmlspecialchars($group['name']) . '<br>';
//                     $output .= '<strong>ID Grup:</strong> <code>' . htmlspecialchars($group['id']) . '</code></li>';
//                 }
//                 $output .= '</ul>';
//             }
//             $output .= '</div></body></html>';

//             return response($output);
//         } else {
//             Log::error('Fonnte: Gagal mengambil daftar grup WhatsApp.', [
//                 'status' => $getGroupsResponse->status(),
//                 'response' => $getGroupsResponse->json()
//             ]);
//             return response('Gagal mengambil daftar grup dari Fonnte. Cek log Laravel.', 500);
//         }
//     } catch (\Exception $e) {
//         Log::error('Fonnte: Exception saat memanggil get-whatsapp-group API: ' . $e->getMessage());
//         return response('Terjadi kesalahan saat mengambil grup: ' . $e->getMessage(), 500);
//     }
// })->name('fonnte.get_group_id_manual'); // Beri nama route untuk kemudahan
