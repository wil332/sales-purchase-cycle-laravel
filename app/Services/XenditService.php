<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class XenditService
{
    protected string $baseUrl;
    protected ?string $secretKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('xendit.base_url', 'https://api.xendit.co'), '/');
        $this->secretKey = config('xendit.secret_key');
    }

    protected function client()
    {
        if (empty($this->secretKey)) {
            throw new RuntimeException('XENDIT_SECRET_KEY belum diset di .env');
        }

        // Xendit pakai Basic Auth: secret key sebagai username, password kosong.
        return Http::withBasicAuth($this->secretKey, '')->acceptJson();
    }

    /**
     * Buat invoice pembayaran baru.
     * $data: ['external_id', 'amount', 'payer_email', 'description', 'currency', ...]
     */
    public function createInvoice(array $data): array
    {
        $payload = [
            'external_id' => $data['external_id'] ?? ('INV-' . Str::upper(Str::random(10))),
            'amount' => $data['amount'],
            'payer_email' => $data['payer_email'] ?? null,
            'description' => $data['description'] ?? 'Pembayaran',
            'currency' => $data['currency'] ?? 'IDR',
            'invoice_duration' => $data['invoice_duration'] ?? 86400, // 24 jam
            'success_redirect_url' => $data['success_redirect_url'] ?? url('/'),
            'failure_redirect_url' => $data['failure_redirect_url'] ?? url('/'),
        ];

        $response = $this->client()->post("{$this->baseUrl}/v2/invoices", $payload);

        if ($response->failed()) {
            throw new RuntimeException('Gagal membuat invoice Xendit: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Ambil status invoice terbaru langsung dari Xendit (polling manual).
     */
    public function getInvoice(string $xenditInvoiceId): array
    {
        $response = $this->client()->get("{$this->baseUrl}/v2/invoices/{$xenditInvoiceId}");

        if ($response->failed()) {
            throw new RuntimeException('Gagal mengambil invoice Xendit: ' . $response->body());
        }

        return $response->json();
    }
}
