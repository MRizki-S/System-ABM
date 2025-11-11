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
        $this->apiKey           = env('FONNTE_API_KEY', '');
        $this->fonnteSendApiUrl = env('FONNTE_SEND_API_URL', 'https://api.fonnte.com/send');

        if (empty($this->apiKey)) {
            Log::warning('Fonnte API Key tidak ditemukan di .env. Pengiriman pesan mungkin gagal.');
        }
    }

    /**
     * Kirim pesan teks ke grup WhatsApp via Fonnte.
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
            // Coba kirim dengan retry 3x, jeda 2 detik antar percobaan, timeout 15 detik
            $response = Http::retry(3, 2000, function ($exception) {
                // hanya retry kalau network error atau timeout
                return $exception instanceof \Illuminate\Http\Client\ConnectionException;
            })
                ->timeout(15)
                ->withHeaders([
                    'Authorization' => $this->apiKey,
                ])
                ->post($this->fonnteSendApiUrl, [
                    'target'  => $targetGroupId,
                    'message' => $message,
                ]);

            // ✅ kalau sukses
            if ($response->successful() && $response->json('status')) {
                Log::info('Pesan WhatsApp berhasil dikirim ke grup.', [
                    'group_id' => $targetGroupId,
                    'response' => $response->json(),
                ]);
                return true;
            }

            // ❌ kalau respon gagal (status 4xx/5xx)
            Log::error('Gagal mengirim pesan WhatsApp ke grup via Fonnte.', [
                'group_id'      => $targetGroupId,
                'status'        => $response->status(),
                'response_body' => $response->body(),
            ]);
            return false;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // timeout / koneksi gagal
            Log::error('Timeout saat mengirim pesan ke Fonnte.', [
                'group_id' => $targetGroupId,
                'error'    => $e->getMessage(),
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('Exception saat mengirim pesan WhatsApp via Fonnte.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}
