<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteMessageService
{
    protected string $apiKey;
    protected string $fonnteSendApiUrl;
    protected string $targetGroupId;

    public function __construct()
    {
        // Ambil nilai dari .env. Pastikan mereka terdefinisi.
        $this->apiKey = env('FONNTE_API_KEY', ''); // String kosong jika tidak ada
        $this->fonnteSendApiUrl = env('FONNTE_SEND_API_URL', 'https://api.fonnte.com/send');
        $this->targetGroupId = env('FONNTE_GROUP_ID', ''); // String kosong jika tidak ada

        // Opsional: Log warning jika ada konfigurasi yang hilang
        if (empty($this->apiKey)) {
            Log::warning('Fonnte API Key tidak ditemukan di .env. Pengiriman pesan mungkin gagal.');
        }
        if (empty($this->targetGroupId)) {
            Log::warning('Fonnte Group ID tidak ditemukan di .env. Pesan ke grup tidak akan terkirim.');
        }
    }

    /**
     * Mengirim pesan teks ke grup WhatsApp yang sudah ditentukan ID-nya.
     *
     * @param string $message Konten pesan yang akan dikirim.
     * @return bool True jika pengiriman berhasil, false jika gagal.
     */
    public function sendToGroup(string $message): bool
    {
        // Pastikan semua kredensial yang dibutuhkan tersedia
        if (empty($this->apiKey) || empty($this->fonnteSendApiUrl) || empty($this->targetGroupId)) {
            Log::error('Fonnte API atau Group ID tidak lengkap. Pesan tidak dapat dikirim.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post($this->fonnteSendApiUrl, [
                'target' => $this->targetGroupId,
                'message' => $message,
            ]);

            // Fonnte API biasanya merespons dengan JSON dan status true/false
            if ($response->successful() && $response->json('status')) {
                Log::info('Pesan WhatsApp berhasil dikirim ke grup.', [
                    'group_id' => $this->targetGroupId,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Gagal mengirim pesan WhatsApp ke grup via Fonnte.', [
                    'group_id' => $this->targetGroupId,
                    'status' => $response->status(),
                    'response_body' => $response->body() // Ambil body respons untuk debugging
                ]);
                return false;
            }
        } catch (\Throwable $e) { // Gunakan \Throwable untuk menangkap error dan exception
            Log::error('Exception saat mengirim pesan WhatsApp via Fonnte: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString() // Untuk debugging lebih lanjut
            ]);
            return false;
        }
    }

    // Anda bisa menambahkan method lain di sini, misalnya:
    // public function sendImageToGroup(string $imageUrl, string $caption): bool { ... }
    // public function sendToIndividual(string $phoneNumber, string $message): bool { ... }
}
