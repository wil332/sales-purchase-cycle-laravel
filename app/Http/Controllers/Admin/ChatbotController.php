<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChatHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function index()
    {
        $histories = ChatHistory::where('user_id', auth()->id())
                        ->latest()
                        ->take(20)
                        ->get()
                        ->reverse();

        $provider = strtolower(env('CHATBOT_PROVIDER', 'openrouter'));
        $model = ($provider === 'groq') 
            ? config('services.groq.model', 'llama-3.3-70b-versatile')
            : config('services.openrouter.model', 'meta-llama/llama-3.1-8b-instruct:free');

        return view('admin.chatbot.index', compact('histories', 'provider', 'model'));
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $provider = strtolower(env('CHATBOT_PROVIDER', 'openrouter'));

        // Konfigurasi endpoint & credential berdasarkan provider (OpenRouter atau Groq)
        if ($provider === 'groq' || (!config('services.openrouter.key') && config('services.groq.key'))) {
            $provider = 'groq';
            $apiKey   = config('services.groq.key');
            $baseUrl  = rtrim(config('services.groq.url', 'https://api.groq.com/openai/v1'), '/');
            $model    = config('services.groq.model', 'llama-3.3-70b-versatile');
            $headers  = [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ];
        } else {
            $provider = 'openrouter';
            $apiKey   = config('services.openrouter.key');
            $baseUrl  = rtrim(config('services.openrouter.url', 'https://openrouter.ai/api/v1'), '/');
            $model    = config('services.openrouter.model', 'meta-llama/llama-3.1-8b-instruct:free');
            $headers  = [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'HTTP-Referer'  => config('app.url', 'http://localhost:8000'),
                'X-Title'       => 'ARIA ERP Assistant',
            ];
        }

        // Cek jika API Key belum diisi
        if (empty($apiKey)) {
            return response()->json([
                'reply' => "⚠️ API Key belum dikonfigurasi di file `.env` untuk provider **{$provider}**.\n\nSilakan tambahkan `OPENROUTER_API_KEY=...` atau `GROQ_API_KEY=...` pada file `.env` Anda."
            ]);
        }

        $systemPrompt = "Kamu adalah **Asisten Internal ERP Perusahaan** bernama **ARIA (Asisten Referensi Internal Aplikasi)**.

Kamu dirancang khusus untuk membantu **staf dan karyawan baru** memahami cara menggunakan aplikasi ERP Purchase & Sales ini.

## Tentang Aplikasi Ini
Aplikasi ini adalah sistem ERP (Enterprise Resource Planning) berbasis web untuk mengelola alur pembelian (Purchase) dan penjualan (Sales) perusahaan elektronik & gadget.

## Alur Pembelian (Purchase Flow):
1. **Purchase Request (PR)** → Permintaan pembelian dari internal
2. **Purchase Order (PO)** → Pesanan resmi ke vendor/supplier. Pilih vendor → otomatis barang yang tampil hanya yang dijual vendor tsb.
3. **Penerimaan Barang (Goods Receipt)** → Konfirmasi barang datang. Pilih No. PO → otomatis terisi barang & sisa kuantitas dari PO.
4. **Tagihan Pembelian (Purchase Invoice)** → Faktur dari vendor. Pilih No. Penerimaan → otomatis terisi barang, qty diterima, dan harga dari PO.
5. **Pembayaran Vendor (Purchase Payment)** → Pelunasan ke vendor. Pilih Invoice → otomatis terisi sisa tagihan.

## Alur Penjualan (Sales Flow):
1. **Sales Order (SO)** → Pesanan dari pelanggan
2. **Pengiriman Barang (Shipment / Surat Jalan)** → Kirim barang ke pelanggan. Pilih No. SO → otomatis terisi barang & sisa qty pesanan.
3. **Tagihan Penjualan (Sales Invoice)** → Faktur ke pelanggan. Pilih No. Surat Jalan → otomatis terisi barang, qty terkirim, harga jual, dan pelanggan.
4. **Pembayaran Pelanggan** → Pelunasan manual (tunai/transfer/cek). Pilih Invoice → otomatis terisi sisa tagihan.
5. **Pembayaran Xendit** → Pembayaran online digital (QRIS, VA, e-wallet). Membuat link invoice yang bisa dibagikan ke pelanggan untuk bayar mandiri secara online.

## Master Data:
- **Kategori Produk** → Klasifikasi barang (Komputer, Peripheral, Storage, Jaringan, Gadget)
- **Data Barang** → Katalog semua produk (SKU BRG001-BRG030), setiap barang memiliki kategori.
- **Vendor / Supplier** → Setiap vendor hanya menjual barang tertentu (sudah diasosiasikan). Saat buat PO, pilihan barang otomatis difilter sesuai vendor.
- **Pelanggan** → Data pelanggan B2B maupun retail
- **Staf / Karyawan** → Data pengguna sistem dengan jabatan: admin, purchasing, gudang, sales, manajer

## Perbedaan Pembayaran Pelanggan vs Xendit:
- **Pembayaran Pelanggan**: Pencatatan manual untuk pembayaran tunai, transfer bank, atau cek dari pelanggan korporat (B2B).
- **Pembayaran Xendit**: Pembayaran digital online via QRIS, Virtual Account, e-wallet. Sistem membuat link invoice otomatis yang dikirim ke pelanggan untuk bayar sendiri.

## Tips Penggunaan Aplikasi:
- Semua form dokumen transaksi memiliki fitur **auto-fill**: cukup pilih dokumen referensi (misal: pilih No. PO di form GR), maka data terkait terisi otomatis.
- Tombol **Acak** pada nomor dokumen akan generate nomor otomatis berformat: `PO-YYYYMM-XXXX`
- SKU barang menggunakan format standar: `BRG001` sampai `BRG030`
- Status dokumen: **0=Batal, 1=Aktif/Valid, 2=Diproses, 3=Selesai/Lunas**

## Cara Bertanya yang Baik:
Kamu bisa bertanya seperti:
- 'Bagaimana cara membuat Purchase Order?'
- 'Apa bedanya Goods Receipt dan Purchase Invoice?'
- 'Mengapa pilihan barang di PO terbatas?'
- 'Bagaimana alur lengkap dari SO sampai barang terkirim?'
- 'Barang apa saja yang masuk kategori Gadget?'
- 'Apa fungsi Xendit di sini?'

Gunakan **Bahasa Indonesia** yang ramah, jelas, dan profesional.
Jika ada pertanyaan di luar konteks aplikasi ERP ini, arahkan kembali ke topik yang relevan dengan sopan.
Jika staf baru bertanya tentang tugasnya, sesuaikan jawaban dengan jabatan yang disebutkan.";

        try {
            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->post($baseUrl . '/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role'    => 'user',
                            'content' => $request->message,
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                $errorData = $response->json();
                $errorMsg  = $errorData['error']['message'] ?? $response->body();
                Log::error("Chatbot API Error ({$provider}): " . $errorMsg);

                return response()->json([
                    'reply' => "⚠️ Maaf, terjadi kendala respon dari provider {$provider}: {$errorMsg}\n\nPastikan API Key dan nama model pada `.env` sudah benar."
                ]);
            }

            $reply = $response->json()['choices'][0]['message']['content'] ?? 'Tidak ada respon yang diterima dari model.';

            ChatHistory::create([
                'user_id'  => auth()->id(),
                'question' => $request->message,
                'answer'   => $reply,
            ]);

            return response()->json([
                'reply' => $reply,
            ]);
        } catch (\Exception $e) {
            Log::error("Chatbot Exception ({$provider}): " . $e->getMessage());

            return response()->json([
                'reply' => "⚠️ Maaf, terjadi kendala saat menghubungi API ({$provider}): " . $e->getMessage()
            ]);
        }
    }
}
