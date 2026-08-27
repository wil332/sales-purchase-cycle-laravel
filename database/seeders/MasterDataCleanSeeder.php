<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterDataCleanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Bersihkan Seluruh Tabel Transaksi & Detail
        $tablesToTruncate = [
            'detail_pelunasan_pembelian',
            'nota_pelunasan_pembelian',
            'detail_tagihan_pembelian',
            'nota_tagihan_pembelian',
            'detail_penerimaan_barang',
            'nota_penerimaan_barang',
            'detail_retur_barang',
            'nota_retur_barang',
            'detail_order_pembelian',
            'nota_order_pembelian',
            'detail_permintaan_pembelian',
            'nota_permintaan_pembelian',

            'nota_pelunasan_penjualan',
            'detail_retur_penjualan',
            'nota_retur_penjualan',
            'detail_tagihan_penjualan',
            'nota_tagihan_penjualan',
            'detail_pengiriman_barang',
            'nota_pengiriman_barang',
            'detail_order_penjualan',
            'nota_order_penjualan',

            'vendor_barang',
            'm_barang',
            'categories',
            'm_vendor',
            'm_pelanggan',
            'm_pengguna',
            'users',
            'users_roles',
        ];

        foreach ($tablesToTruncate as $table) {
            DB::table($table)->truncate();
        }

        $now = Carbon::now();

        // 2. Seed Kategori Produk
        $categories = [
            [
                'id' => 1,
                'name' => 'Komputer & Laptop',
                'description' => 'Laptop, PC Desktop, Server, All-in-One, dan perangkat komputasi utama.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Peripheral & Aksesoris',
                'description' => 'Mouse, Keyboard, Webcam, Headset, Speaker, Printer, dan perangkat input/output.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Komponen & Penyimpanan',
                'description' => 'RAM, SSD, Harddisk Eksternal, Flashdisk, UPS, dan media penyimpanan data.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Jaringan & Komunikasi',
                'description' => 'Router Wi-Fi, Switch Hub, USB Hub, Kabel HDMI/LAN, dan perangkat networking.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Gadget & Multimedia',
                'description' => 'Smartphone, Tablet, Smartwatch, Kamera Digital, Drone, dan Projector.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('categories')->insert($categories);

        // 3. Seed Master Barang (100% Memiliki category_id)
        $products = [
            // Kategori 1: Komputer & Laptop
            ['sku' => 'BRG001', 'category_id' => 1, 'nama_barang' => 'Laptop ASUS Vivobook 14', 'keterangan' => 'Intel Core i5, RAM 8GB, SSD 512GB, Windows 11', 'status' => 1],
            ['sku' => 'BRG002', 'category_id' => 1, 'nama_barang' => 'Laptop Lenovo IdeaPad Slim 3', 'keterangan' => 'AMD Ryzen 5, RAM 16GB, SSD 512GB, Layar 14 inch FHD', 'status' => 1],
            ['sku' => 'BRG005', 'category_id' => 1, 'nama_barang' => 'Monitor LG 24" IPS FHD', 'keterangan' => 'Resolusi 1920x1080, Refresh Rate 75Hz, Port HDMI/VGA', 'status' => 1],
            ['sku' => 'BRG018', 'category_id' => 1, 'nama_barang' => 'Cooling Pad Laptop Dual Fan', 'keterangan' => 'Bahan aluminium dengan 2 kipas pendingin silent LED', 'status' => 1],

            // Kategori 2: Peripheral & Aksesoris
            ['sku' => 'BRG003', 'category_id' => 2, 'nama_barang' => 'Mouse Wireless Logitech M185', 'keterangan' => 'Koneksi 2.4GHz USB Nano Receiver, baterai tahan 12 bulan', 'status' => 1],
            ['sku' => 'BRG004', 'category_id' => 2, 'nama_barang' => 'Keyboard Mechanical RGB Blue Switch', 'keterangan' => 'Tenkeyless 87 keys, backlit RGB, kabel braided', 'status' => 1],
            ['sku' => 'BRG006', 'category_id' => 2, 'nama_barang' => 'Printer Epson EcoTank L3210', 'keterangan' => 'All-in-One Print, Scan, Copy dengan sistem infus resmi', 'status' => 1],
            ['sku' => 'BRG012', 'category_id' => 2, 'nama_barang' => 'Webcam Logitech C920 Pro', 'keterangan' => 'Full HD 1080p, stereo audio dengan dual mic, autofocus', 'status' => 1],
            ['sku' => 'BRG013', 'category_id' => 2, 'nama_barang' => 'Headset Gaming 7.1 Surround', 'keterangan' => 'Driver 50mm, noise-cancelling mic, bantalan busa empuk', 'status' => 1],
            ['sku' => 'BRG014', 'category_id' => 2, 'nama_barang' => 'Speaker Bluetooth Portable 20W', 'keterangan' => 'Suara bass jernih, tahan air IPX7, baterai tahan 10 jam', 'status' => 1],
            ['sku' => 'BRG024', 'category_id' => 2, 'nama_barang' => 'Microphone USB Condenser Studio', 'keterangan' => 'Pola pickup Cardioid untuk rekaman suara vokal dan podcast', 'status' => 1],

            // Kategori 3: Komponen & Penyimpanan
            ['sku' => 'BRG007', 'category_id' => 3, 'nama_barang' => 'Flashdisk SanDisk Ultra 64GB', 'keterangan' => 'Kecepatan transfer USB 3.0 hingga 130 MB/s', 'status' => 1],
            ['sku' => 'BRG008', 'category_id' => 3, 'nama_barang' => 'SSD Kingston NV2 500GB PCIe 4.0', 'keterangan' => 'Form Factor M.2 NVMe, Read speed up to 3500 MB/s', 'status' => 1],
            ['sku' => 'BRG009', 'category_id' => 3, 'nama_barang' => 'Harddisk Eksternal WD Elements 1TB', 'keterangan' => 'Portabel 2.5 inch USB 3.0, kompatibel Windows & Mac', 'status' => 1],
            ['sku' => 'BRG010', 'category_id' => 3, 'nama_barang' => 'RAM Kingston Fury DDR4 16GB 3200MHz', 'keterangan' => 'Modul 2x8GB dual channel dengan heatsink aluminium', 'status' => 1],
            ['sku' => 'BRG028', 'category_id' => 3, 'nama_barang' => 'UPS APC Back-UPS 650VA / 390W', 'keterangan' => 'Pelindung lonjakan listrik dengan cadangan baterai', 'status' => 1],
            ['sku' => 'BRG030', 'category_id' => 3, 'nama_barang' => 'NAS Storage Synology 2-Bay', 'keterangan' => 'Penyimpanan server cloud lokal untuk backup file kantor', 'status' => 1],

            // Kategori 4: Jaringan & Komunikasi
            ['sku' => 'BRG011', 'category_id' => 4, 'nama_barang' => 'Router Wi-Fi TP-Link Archer AX10', 'keterangan' => 'Wi-Fi 6 Dual Band Gigabit hingga 1500 Mbps', 'status' => 1],
            ['sku' => 'BRG016', 'category_id' => 4, 'nama_barang' => 'Kabel HDMI 2.1 Ultra High Speed 2M', 'keterangan' => 'Mendukung resolusi 4K 120Hz dan 8K 60Hz HDR', 'status' => 1],
            ['sku' => 'BRG017', 'category_id' => 4, 'nama_barang' => 'USB-C Hub Multiport 7-in-1', 'keterangan' => 'Port 4K HDMI, 3x USB 3.0, SD/TF Card Reader, PD 100W', 'status' => 1],
            ['sku' => 'BRG029', 'category_id' => 4, 'nama_barang' => 'Gigabit Switch 8-Port TP-Link', 'keterangan' => '8 port RJ45 10/100/1000 Mbps, casing metal plug & play', 'status' => 1],

            // Kategori 5: Gadget & Multimedia
            ['sku' => 'BRG015', 'category_id' => 5, 'nama_barang' => 'Power Bank Anker 20000mAh PD 20W', 'keterangan' => 'Pengisian cepat Power Delivery, dual output USB-A & C', 'status' => 1],
            ['sku' => 'BRG019', 'category_id' => 5, 'nama_barang' => 'Scanner Flatbed Canon LiDE 300', 'keterangan' => 'Pemindaian dokumen A4 resolusi 2400x2400 dpi cepat', 'status' => 1],
            ['sku' => 'BRG020', 'category_id' => 5, 'nama_barang' => 'Projector Epson EB-E500 XGA', 'keterangan' => 'Kecerahan 3300 Lumens, teknologi 3LCD jernih', 'status' => 1],
            ['sku' => 'BRG021', 'category_id' => 5, 'nama_barang' => 'Smartphone Samsung Galaxy A54 5G', 'keterangan' => 'Layar 6.4 inch Super AMOLED 120Hz, RAM 8GB, Internal 256GB', 'status' => 1],
            ['sku' => 'BRG022', 'category_id' => 5, 'nama_barang' => 'Tablet Xiaomi Pad 6 11 inch', 'keterangan' => 'Snapdragon 870, Layar 144Hz WQHD+, Baterai 8840mAh', 'status' => 1],
            ['sku' => 'BRG023', 'category_id' => 5, 'nama_barang' => 'Smartwatch Huawei Watch Fit 2', 'keterangan' => 'Layar AMOLED 1.74 inch, GPS terintegrasi, pemantau detak jantung', 'status' => 1],
            ['sku' => 'BRG025', 'category_id' => 5, 'nama_barang' => 'Kamera Mirrorless Canon EOS R50', 'keterangan' => 'Sensor CMOS APS-C 24.2 MP dengan lensa kit 18-45mm', 'status' => 1],
            ['sku' => 'BRG026', 'category_id' => 5, 'nama_barang' => 'Tripod Kamera Aluminium Professional', 'keterangan' => 'Tinggi maks 160cm, ball head 360 derajat, beban maks 5kg', 'status' => 1],
            ['sku' => 'BRG027', 'category_id' => 5, 'nama_barang' => 'Drone DJI Mini 2 SE 4K', 'keterangan' => 'Bobot 249g, jarak terbang hingga 10km, video 4K jernih', 'status' => 1],
        ];

        foreach ($products as &$p) {
            $p['created_at'] = $now;
            $p['updated_at'] = $now;
        }
        DB::table('m_barang')->insert($products);

        // 4. Seed Master Vendor
        $vendors = [
            [
                'id_vendor' => 1,
                'nama_vendor' => 'PT Sumber Makmur Komputer',
                'no_telp' => '021-5551234',
                'alamat' => 'Kawasan Industri Pulogadung Blok B No. 12, Jakarta Timur',
                'keterangan' => 'Distributor resmi laptop, PC desktop, monitor, dan aksesoris komputasi.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_vendor' => 2,
                'nama_vendor' => 'PT Sinar Abadi Peripheral',
                'no_telp' => '021-8894455',
                'alamat' => 'Ruko Mangga Dua Mall Lt. 3 No. 45, Jakarta Pusat',
                'keterangan' => 'Pemasok utama mouse, keyboard, headset, printer, dan aksesoris audio studio.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_vendor' => 3,
                'nama_vendor' => 'CV Global Storage & Network',
                'no_telp' => '031-7788990',
                'alamat' => 'Kompleks Pergudangan Margomulyo Indah Blok D-8, Surabaya',
                'keterangan' => 'Supplier komponen storage (SSD/HDD/RAM), UPS, dan perangkat jaringan enterprise.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_vendor' => 4,
                'nama_vendor' => 'PT Mega Gadget Multimedia',
                'no_telp' => '022-4455667',
                'alamat' => 'Jl. Ir. H. Juanda No. 108, Dago, Bandung',
                'keterangan' => 'Distributor resmi gadget mobile, tablet, smartwatch, proyektor, dan kamera.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('m_vendor')->insert($vendors);

        // 5. Seed Asosiasi Vendor ↔ Barang (vendor_barang)
        $vendorBarangMap = [
            1 => ['BRG001', 'BRG002', 'BRG005', 'BRG018'],
            2 => ['BRG003', 'BRG004', 'BRG006', 'BRG012', 'BRG013', 'BRG014', 'BRG024'],
            3 => ['BRG007', 'BRG008', 'BRG009', 'BRG010', 'BRG011', 'BRG016', 'BRG017', 'BRG028', 'BRG029', 'BRG030'],
            4 => ['BRG015', 'BRG019', 'BRG020', 'BRG021', 'BRG022', 'BRG023', 'BRG025', 'BRG026', 'BRG027'],
        ];

        $vendorBarangData = [];
        foreach ($vendorBarangMap as $idVendor => $skus) {
            foreach ($skus as $sku) {
                $vendorBarangData[] = [
                    'id_vendor' => $idVendor,
                    'sku' => $sku,
                    'harga_beli' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        DB::table('vendor_barang')->insert($vendorBarangData);

        // 6. Seed Master Pelanggan
        $pelanggan = [
            [
                'id_pelanggan' => 1,
                'nama_pelanggan' => 'PT Graha Solusi Teknologi',
                'no_telp' => '021-7788112',
                'alamat' => 'Sudirman Central Business District (SCBD) Tower 2, Jakarta Selatan',
                'keterangan' => 'Klien korporat pengadaan perangkat IT & komputer kantor',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pelanggan' => 2,
                'nama_pelanggan' => 'CV Prima Citra Mandiri',
                'no_telp' => '022-8765432',
                'alamat' => 'Jl. Buah Batu No. 210, Bandung',
                'keterangan' => 'Perusahaan konsultan media kreatif dan multimedia',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pelanggan' => 3,
                'nama_pelanggan' => 'Toko Komputer Mitra Jaya',
                'no_telp' => '031-5544332',
                'alamat' => 'Hitech Mall Lt. 1 No. 18, Surabaya',
                'keterangan' => 'Mitra reseller retail komponen & aksesoris PC',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pelanggan' => 4,
                'nama_pelanggan' => 'Nina Anastasia',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Boulevard Hijau No. 15, Harapan Indah, Bekasi',
                'keterangan' => 'Pelanggan retail perorangan',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pelanggan' => 5,
                'nama_pelanggan' => 'Budi Santoso',
                'no_telp' => '085678901234',
                'alamat' => 'Jl. Kaliurang KM 5 No. 88, Yogyakarta',
                'keterangan' => 'Pelanggan retail perorangan',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('m_pelanggan')->insert($pelanggan);

        // 7. Seed Master Pengguna (m_pengguna) & User Login (users)
        $pengguna = [
            [
                'id_pengguna' => 1,
                'nama_lengkap' => 'Administrator Utama',
                'username' => 'admin',
                'password' => bcrypt('admin123'),
                'jabatan' => 'admin',
                'keterangan' => 'Pengelola sistem utama & konfigurasi',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pengguna' => 2,
                'nama_lengkap' => 'Ahmad Purchasing',
                'username' => 'purchasing',
                'password' => bcrypt('purchasing123'),
                'jabatan' => 'purchasing',
                'keterangan' => 'Staf divisi pengadaan barang & vendor',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pengguna' => 3,
                'nama_lengkap' => 'Guntur Gudang',
                'username' => 'gudang',
                'password' => bcrypt('gudang123'),
                'jabatan' => 'gudang',
                'keterangan' => 'Staf operasional penerimaan & pengiriman logistik',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pengguna' => 4,
                'nama_lengkap' => 'Siti Sales',
                'username' => 'sales',
                'password' => bcrypt('sales123'),
                'jabatan' => 'sales',
                'keterangan' => 'Staf bagian penjualan & penawaran pelanggan',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pengguna' => 5,
                'nama_lengkap' => 'Mega Manajer',
                'username' => 'manajer',
                'password' => bcrypt('manajer123'),
                'jabatan' => 'manajer',
                'keterangan' => 'Kepala operasional & audit transaksi',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('m_pengguna')->insert($pengguna);

        // Seed tabel `users` untuk autentikasi auth Laravel
        $authUsers = [
            [
                'id' => 1,
                'name' => 'Administrator Utama',
                'email' => 'admin@perusahaan.com',
                'password' => bcrypt('admin123'),
                'active' => true,
                'confirmation_code' => \Ramsey\Uuid\Uuid::uuid4(),
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Ahmad Purchasing',
                'email' => 'purchasing@perusahaan.com',
                'password' => bcrypt('purchasing123'),
                'active' => true,
                'confirmation_code' => \Ramsey\Uuid\Uuid::uuid4(),
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Guntur Gudang',
                'email' => 'gudang@perusahaan.com',
                'password' => bcrypt('gudang123'),
                'active' => true,
                'confirmation_code' => \Ramsey\Uuid\Uuid::uuid4(),
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Siti Sales',
                'email' => 'sales@perusahaan.com',
                'password' => bcrypt('sales123'),
                'active' => true,
                'confirmation_code' => \Ramsey\Uuid\Uuid::uuid4(),
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Mega Manajer',
                'email' => 'manajer@perusahaan.com',
                'password' => bcrypt('manajer123'),
                'active' => true,
                'confirmation_code' => \Ramsey\Uuid\Uuid::uuid4(),
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'name' => 'Admin Boilerplate',
                'email' => 'admin.laravel@labs64.com',
                'password' => bcrypt('admin'),
                'active' => true,
                'confirmation_code' => \Ramsey\Uuid\Uuid::uuid4(),
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'name' => 'Demo User',
                'email' => 'demo.laravel@labs64.com',
                'password' => bcrypt('demo'),
                'active' => true,
                'confirmation_code' => \Ramsey\Uuid\Uuid::uuid4(),
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'name' => 'Wilbert',
                'email' => 'wilbert@gmail.com',
                'password' => bcrypt('admin123'),
                'active' => true,
                'confirmation_code' => \Ramsey\Uuid\Uuid::uuid4(),
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('users')->insert($authUsers);

        // Assign roles (1: administrator, 2: authenticated) ke semua user
        $userRoles = [];
        for ($u = 1; $u <= 8; $u++) {
            $userRoles[] = ['user_id' => $u, 'role_id' => 1];
            $userRoles[] = ['user_id' => $u, 'role_id' => 2];
        }
        DB::table('users_roles')->insert($userRoles);

        // 8. Seed 1 Set Transaksi Pembelian (Purchase Flow) Standar Resmi
        $poNumber = 'PO-202608-0001';
        $grNumber = 'GR-202608-0001';
        $pinvNumber = 'PINV-202608-0001';
        $ppayNumber = 'PPAY-202608-0001';

        // Header PO
        DB::table('nota_order_pembelian')->insert([
            'no_order_pembelian' => $poNumber,
            'tanggal' => $now->toDateString(),
            'id_vendor' => 1, // PT Sumber Makmur Komputer
            'id_pengguna' => 2, // Ahmad Purchasing
            'tanggal_dibutuhkan' => $now->copy()->addDays(7)->toDateString(),
            'keterangan' => 'Pengadaan unit laptop dan monitor untuk restock cabang Jakarta',
            'status' => 3, // Selesai (karena barang sudah diterima penuh)
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Detail PO
        DB::table('detail_order_pembelian')->insert([
            [
                'no_order_pembelian' => $poNumber,
                'sku' => 'BRG001',
                'kuantitas' => 5,
                'harga_unit' => 7500000.00,
                'diskon' => 0.00,
                'total_harga' => 37500000.00,
                'keterangan' => 'ASUS Vivobook 14',
            ],
            [
                'no_order_pembelian' => $poNumber,
                'sku' => 'BRG005',
                'kuantitas' => 10,
                'harga_unit' => 1650000.00,
                'diskon' => 0.00,
                'total_harga' => 16500000.00,
                'keterangan' => 'Monitor LG 24"',
            ],
        ]);

        // Header Goods Receipt
        DB::table('nota_penerimaan_barang')->insert([
            'no_penerimaan' => $grNumber,
            'tanggal_penerimaan' => $now->toDateString(),
            'no_order_pembelian' => $poNumber,
            'id_pengguna' => 3, // Guntur Gudang
            'keterangan' => 'Penerimaan barang dari PO ' . $poNumber . ' dalam kondisi lengkap dan baik',
            'status' => 1, // Diterima
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Detail Goods Receipt
        DB::table('detail_penerimaan_barang')->insert([
            [
                'no_penerimaan' => $grNumber,
                'sku' => 'BRG001',
                'kuantitas' => 5,
                'kondisi_barang' => 'baik',
                'keterangan' => '5 unit lengkap segel utuh',
            ],
            [
                'no_penerimaan' => $grNumber,
                'sku' => 'BRG005',
                'kuantitas' => 10,
                'kondisi_barang' => 'baik',
                'keterangan' => '10 unit monitor kardus mulus',
            ],
        ]);

        // Header Purchase Invoice
        DB::table('nota_tagihan_pembelian')->insert([
            'no_invoice_beli' => $pinvNumber,
            'tanggal' => $now->toDateString(),
            'no_penerimaan' => $grNumber,
            'id_vendor' => 1,
            'id_pengguna' => 2,
            'jatuh_tempo' => $now->copy()->addDays(30)->toDateString(),
            'keterangan' => 'Faktur tagihan vendor atas penerimaan ' . $grNumber,
            'status' => 3, // Lunas
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Detail Purchase Invoice (Total: 5*7.5jt + 10*1.65jt = 37.5jt + 16.5jt = 54.000.000)
        DB::table('detail_tagihan_pembelian')->insert([
            [
                'no_invoice_beli' => $pinvNumber,
                'sku' => 'BRG001',
                'kuantitas' => 5,
                'harga_unit' => 7500000.00,
                'diskon' => 0.00,
                'total_harga' => 37500000.00,
                'keterangan' => 'Tagihan 5 unit Laptop ASUS',
            ],
            [
                'no_invoice_beli' => $pinvNumber,
                'sku' => 'BRG005',
                'kuantitas' => 10,
                'harga_unit' => 1650000.00,
                'diskon' => 0.00,
                'total_harga' => 16500000.00,
                'keterangan' => 'Tagihan 10 unit Monitor LG',
            ],
        ]);

        // Header Purchase Payment
        DB::table('nota_pelunasan_pembelian')->insert([
            'no_pembayaran_beli' => $ppayNumber,
            'tanggal_bayar' => $now->toDateString(),
            'no_invoice_beli' => $pinvNumber,
            'id_pengguna' => 1,
            'jumlah_bayar' => 54000000.00,
            'metode_bayar' => 'transfer',
            'keterangan' => 'Pelunasan penuh via transfer bank Mandiri',
            'status' => 1, // Valid
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Detail Purchase Payment
        DB::table('detail_pelunasan_pembelian')->insert([
            'no_pembayaran_beli' => $ppayNumber,
            'no_invoice_beli' => $pinvNumber,
            'jumlah_dialokasikan' => 54000000.00,
            'keterangan' => 'Alokasi lunas penuh',
        ]);

        // 9. Seed 1 Set Transaksi Penjualan (Sales Flow) Standar Resmi
        $soNumber = 'SO-202608-0001';
        $shpNumber = 'SHP-202608-0001';
        $sinvNumber = 'SINV-202608-0001';
        $spayNumber = 'SPAY-202608-0001';

        // Header Sales Order
        DB::table('nota_order_penjualan')->insert([
            'no_order_penjualan' => $soNumber,
            'tanggal' => $now->toDateString(),
            'id_pelanggan' => 1, // PT Graha Solusi Teknologi
            'id_pengguna' => 4, // Siti Sales
            'keterangan' => 'Pesanan pengadaan perlengkapan IT workstation kantor baru',
            'status' => 3, // Selesai (karena barang sudah dikirim penuh)
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Detail Sales Order
        DB::table('detail_order_penjualan')->insert([
            [
                'no_order_penjualan' => $soNumber,
                'sku' => 'BRG001',
                'kuantitas' => 2,
                'harga_jual' => 8800000.00,
                'diskon' => 0.00,
                'total_harga' => 17600000.00,
                'keterangan' => 'Laptop ASUS Vivobook',
            ],
            [
                'no_order_penjualan' => $soNumber,
                'sku' => 'BRG003',
                'kuantitas' => 5,
                'harga_jual' => 185000.00,
                'diskon' => 0.00,
                'total_harga' => 925000.00,
                'keterangan' => 'Mouse Wireless Logitech',
            ],
        ]);

        // Header Shipment
        DB::table('nota_pengiriman_barang')->insert([
            'no_pengiriman' => $shpNumber,
            'tanggal_kirim' => $now->toDateString(),
            'no_order_penjualan' => $soNumber,
            'id_pengguna' => 3, // Guntur Gudang
            'keterangan' => 'Pengiriman via kurir internal ke SCBD Tower 2',
            'status' => 2, // Telah Diterima
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Detail Shipment
        DB::table('detail_pengiriman_barang')->insert([
            [
                'no_pengiriman' => $shpNumber,
                'sku' => 'BRG001',
                'kuantitas_dikirim' => 2,
                'keterangan' => '2 unit laptop tersegel',
            ],
            [
                'no_pengiriman' => $shpNumber,
                'sku' => 'BRG003',
                'kuantitas_dikirim' => 5,
                'keterangan' => '5 unit mouse wireless',
            ],
        ]);

        // Header Sales Invoice
        DB::table('nota_tagihan_penjualan')->insert([
            'no_invoice_jual' => $sinvNumber,
            'tanggal' => $now->toDateString(),
            'no_pengiriman' => $shpNumber,
            'id_pelanggan' => 1,
            'jatuh_tempo' => $now->copy()->addDays(14)->toDateString(),
            'keterangan' => 'Faktur tagihan atas Surat Jalan ' . $shpNumber,
            'status' => 3, // Lunas
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Detail Sales Invoice (Total: 2*8.8jt + 5*185rb = 17.6jt + 925rb = 18.525.000)
        DB::table('detail_tagihan_penjualan')->insert([
            [
                'no_invoice_jual' => $sinvNumber,
                'sku' => 'BRG001',
                'kuantitas' => 2,
                'harga_jual' => 8800000.00,
                'diskon' => 0.00,
                'total_harga' => 17600000.00,
                'keterangan' => '2 unit Laptop ASUS',
            ],
            [
                'no_invoice_jual' => $sinvNumber,
                'sku' => 'BRG003',
                'kuantitas' => 5,
                'harga_jual' => 185000.00,
                'diskon' => 0.00,
                'total_harga' => 925000.00,
                'keterangan' => '5 unit Mouse Logitech',
            ],
        ]);

        // Header Sales Payment
        DB::table('nota_pelunasan_penjualan')->insert([
            'no_pembayaran' => $spayNumber,
            'tanggal_bayar' => $now->toDateString(),
            'no_invoice_jual' => $sinvNumber,
            'jumlah_bayar' => 18525000.00,
            'metode_bayar' => 'transfer',
            'keterangan' => 'Pelunasan penuh transfer BCA dari PT Graha Solusi Teknologi',
            'status' => 1, // Valid
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
