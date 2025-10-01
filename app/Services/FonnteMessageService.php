<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteMessageService
{
    protected string $apiKey;
    protected string $fonnteSendApiUrl;

    public function __construct()
    {
        // Ambil dari .env
        $this->apiKey           = env('FONNTE_API_KEY', '');
        $this->fonnteSendApiUrl = env('FONNTE_SEND_API_URL', 'https://api.fonnte.com/send');

        if (empty($this->apiKey)) {
            Log::warning('Fonnte API Key tidak ditemukan di .env. Pengiriman pesan mungkin gagal.');
        }
    }

    /**
     * Kirim pesan teks ke grup WhatsApp.
     *
     * @param string $targetGroupId ID grup dari tabel perumahaan
     * @param string $message Konten pesan
     * @return bool
     */
    public function sendToGroup(string $targetGroupId, string $message): bool
    {
        if (empty($this->apiKey) || empty($this->fonnteSendApiUrl) || empty($targetGroupId)) {
            Log::error('Fonnte API, URL, atau Group ID kosong. Pesan tidak terkirim.', [
                'target_group_id' => $targetGroupId,
            ]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post($this->fonnteSendApiUrl, [
                'target'  => $targetGroupId,
                'message' => $message,
            ]);

            if ($response->successful() && $response->json('status')) {
                Log::info('Pesan WhatsApp berhasil dikirim ke grup.', [
                    'group_id' => $targetGroupId,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Gagal mengirim pesan WhatsApp ke grup via Fonnte.', [
                    'group_id'      => $targetGroupId,
                    'status'        => $response->status(),
                    'response_body' => $response->body()
                ]);
                return false;
            }
        } catch (\Throwable $e) {
            Log::error('Exception saat mengirim pesan WhatsApp via Fonnte: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
