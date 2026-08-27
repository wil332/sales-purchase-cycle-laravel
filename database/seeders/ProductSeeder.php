<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
    'sku' => 'BRG001',
    'nama_barang' => 'Laptop ASUS Vivobook',
    'keterangan' => 'Laptop 14 inch',
    'status' => 1,
],
[
    'sku' => 'BRG002',
    'nama_barang' => 'Laptop Lenovo IdeaPad',
    'keterangan' => 'Laptop Office',
    'status' => 1,
],
[
    'sku' => 'BRG003',
    'nama_barang' => 'Mouse Logitech M185',
    'keterangan' => 'Wireless Mouse',
    'status' => 1,
],
[
    'sku' => 'BRG004',
    'nama_barang' => 'Keyboard Mechanical',
    'keterangan' => 'RGB Keyboard',
    'status' => 1,
],
[
    'sku' => 'BRG005',
    'nama_barang' => 'Monitor LG 24"',
    'keterangan' => 'IPS Full HD',
    'status' => 1,
],
[
    'sku' => 'BRG006',
    'nama_barang' => 'Printer Epson L3210',
    'keterangan' => 'Ink Tank',
    'status' => 1,
],
[
    'sku' => 'BRG007',
    'nama_barang' => 'Flashdisk Sandisk 64GB',
    'keterangan' => 'USB 3.0',
    'status' => 1,
],
[
    'sku' => 'BRG008',
    'nama_barang' => 'SSD Kingston 500GB',
    'keterangan' => 'SATA SSD',
    'status' => 1,
],
[
    'sku' => 'BRG009',
    'nama_barang' => 'Harddisk WD 1TB',
    'keterangan' => 'External HDD',
    'status' => 1,
],
[
    'sku' => 'BRG010',
    'nama_barang' => 'RAM DDR4 16GB',
    'keterangan' => '3200 MHz',
    'status' => 1,
],
[
    'sku' => 'BRG011',
    'nama_barang' => 'Router TP-Link',
    'keterangan' => 'Dual Band',
    'status' => 1,
],
[
    'sku' => 'BRG012',
    'nama_barang' => 'Webcam Logitech',
    'keterangan' => '1080P',
    'status' => 1,
],
[
    'sku' => 'BRG013',
    'nama_barang' => 'Headset Gaming',
    'keterangan' => 'Surround Sound',
    'status' => 1,
],
[
    'sku' => 'BRG014',
    'nama_barang' => 'Speaker Bluetooth',
    'keterangan' => 'Portable',
    'status' => 1,
],
[
    'sku' => 'BRG015',
    'nama_barang' => 'Power Bank 20000mAh',
    'keterangan' => 'Fast Charging',
    'status' => 1,
],
[
    'sku' => 'BRG016',
    'nama_barang' => 'Kabel HDMI',
    'keterangan' => '2 Meter',
    'status' => 1,
],
[
    'sku' => 'BRG017',
    'nama_barang' => 'USB Hub',
    'keterangan' => '4 Port',
    'status' => 1,
],
[
    'sku' => 'BRG018',
    'nama_barang' => 'Cooling Pad Laptop',
    'keterangan' => 'Dual Fan',
    'status' => 1,
],
[
    'sku' => 'BRG019',
    'nama_barang' => 'Scanner Canon',
    'keterangan' => 'Flatbed Scanner',
    'status' => 1,
],
[
    'sku' => 'BRG020',
    'nama_barang' => 'Projector Epson',
    'keterangan' => 'XGA Projector',
    'status' => 1,
],
[
    'sku' => 'BRG021',
    'nama_barang' => 'Smartphone Samsung',
    'keterangan' => 'Android',
    'status' => 1,
],
[
    'sku' => 'BRG022',
    'nama_barang' => 'Tablet Xiaomi',
    'keterangan' => '10 Inch',
    'status' => 1,
],
[
    'sku' => 'BRG023',
    'nama_barang' => 'Smartwatch Huawei',
    'keterangan' => 'Fitness Tracker',
    'status' => 1,
],
[
    'sku' => 'BRG024',
    'nama_barang' => 'Microphone USB',
    'keterangan' => 'Condenser Mic',
    'status' => 1,
],
[
    'sku' => 'BRG025',
    'nama_barang' => 'Kamera Canon EOS',
    'keterangan' => 'Mirrorless',
    'status' => 1,
],
[
    'sku' => 'BRG026',
    'nama_barang' => 'Tripod Kamera',
    'keterangan' => 'Aluminium',
    'status' => 1,
],
[
    'sku' => 'BRG027',
    'nama_barang' => 'Drone DJI Mini',
    'keterangan' => '4K Camera',
    'status' => 1,
],
[
    'sku' => 'BRG028',
    'nama_barang' => 'UPS APC',
    'keterangan' => '650VA',
    'status' => 1,
],
[
    'sku' => 'BRG029',
    'nama_barang' => 'Switch 8 Port',
    'keterangan' => 'Gigabit',
    'status' => 1,
],
[
    'sku' => 'BRG030',
    'nama_barang' => 'NAS Storage',
    'keterangan' => '2 Bay',
    'status' => 1,
],
        ];

        foreach ($products as $product) {
            \DB::table('m_barang')->insert($product);
        }
    }
}



