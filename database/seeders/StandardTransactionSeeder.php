<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StandardTransactionSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'detail_permintaan_pembelian', 'nota_permintaan_pembelian',
            'detail_order_pembelian', 'nota_order_pembelian',
            'detail_penerimaan_barang', 'nota_penerimaan_barang',
            'detail_tagihan_pembelian', 'nota_tagihan_pembelian',
            'detail_pelunasan_pembelian', 'nota_pelunasan_pembelian',
            'detail_retur_barang', 'nota_retur_barang',
            'detail_order_penjualan', 'nota_order_penjualan',
            'detail_pengiriman_barang', 'nota_pengiriman_barang',
            'detail_tagihan_penjualan', 'nota_tagihan_penjualan',
            'nota_pelunasan_penjualan',
            'detail_retur_penjualan', 'nota_retur_penjualan'
        ];

        foreach ($tables as $t) {
            if (\Schema::hasTable($t)) {
                DB::table($t)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $today = Carbon::now()->format('Y-m-d');
        $dateCode = Carbon::now()->format('Ym');

        // 1. Seed Purchase Order (PO-YYYYMM-0001)
        DB::table('nota_order_pembelian')->insert([
            'no_order_pembelian' => 'PO-' . $dateCode . '-0001',
            'tanggal' => $today,
            'id_vendor' => 4, // PT Sumber Makmur Abadi
            'id_pengguna' => 6, // Rahel Radiansyah (purchasing)
            'tanggal_dibutuhkan' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'keterangan' => 'Pengadaan Stok Laptop & Peripheral Kantor',
            'status' => 1, // Aktif
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('detail_order_pembelian')->insert([
            [
                'no_order_pembelian' => 'PO-' . $dateCode . '-0001',
                'sku' => 'BRG001', // Laptop ASUS Vivobook
                'kuantitas' => 5,
                'harga_unit' => 7500000,
                'diskon' => 0,
                'total_harga' => 37500000,
                'keterangan' => 'Unit garansi resmi',
            ],
            [
                'no_order_pembelian' => 'PO-' . $dateCode . '-0001',
                'sku' => 'BRG003', // Mouse Logitech
                'kuantitas' => 10,
                'harga_unit' => 150000,
                'diskon' => 0,
                'total_harga' => 1500000,
                'keterangan' => 'Warna hitam',
            ]
        ]);

        // 2. Seed Sales Order (SO-YYYYMM-0001)
        DB::table('nota_order_penjualan')->insert([
            'no_order_penjualan' => 'SO-' . $dateCode . '-0001',
            'tanggal' => $today,
            'id_pelanggan' => 1, // Siti
            'id_pengguna' => 5, // Jimmy Ferguso (sales)
            'keterangan' => 'Pesanan Corporate Paket Laptop',
            'status' => 2, // Diproses
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('detail_order_penjualan')->insert([
            [
                'no_order_penjualan' => 'SO-' . $dateCode . '-0001',
                'sku' => 'BRG001',
                'kuantitas' => 2,
                'harga_jual' => 8500000,
                'diskon' => 0,
                'total_harga' => 17000000,
                'keterangan' => 'Siap kirim besok',
            ]
        ]);

        // 3. Seed Sales Order (SO-YYYYMM-0002)
        DB::table('nota_order_penjualan')->insert([
            'no_order_penjualan' => 'SO-' . $dateCode . '-0002',
            'tanggal' => $today,
            'id_pelanggan' => 3, // Nina
            'id_pengguna' => 5,
            'keterangan' => 'Pembelian Aksesoris Komputer',
            'status' => 3, // Selesai
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('detail_order_penjualan')->insert([
            [
                'no_order_penjualan' => 'SO-' . $dateCode . '-0002',
                'sku' => 'BRG003',
                'kuantitas' => 1,
                'harga_jual' => 175000,
                'diskon' => 0,
                'total_harga' => 175000,
                'keterangan' => 'Lunas via Xendit',
            ]
        ]);
    }
}
