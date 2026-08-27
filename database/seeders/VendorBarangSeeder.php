<?php

namespace Database\Seeders;

use App\Models\Master\Vendor;
use App\Models\Master\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendor_barang')->truncate();

        // Vendor: PT Sumber Makmur Abadi (Elektronik & Komputer)
        $vendorElektronik = Vendor::where('nama_vendor', 'like', '%Sumber Makmur%')->first();
        if ($vendorElektronik) {
            // Barang elektronik, peripheral, storage, gadget
            $skusElektronik = Barang::where('sku', 'like', 'BRG%')
                ->where('sku', '!=', 'BRG034') // bukan kopi
                ->orWhere('sku', 'like', 'ELK%')
                ->pluck('sku')
                ->toArray();

            $vendorElektronik->barangs()->sync($skusElektronik);
        }

        // Vendor: PT Pemasok Kopi Nusantara (Kopi & Bahan Makanan)
        $vendorKopi = Vendor::where('nama_vendor', 'like', '%Kopi%')->first();
        if ($vendorKopi) {
            $skusKopi = Barang::where('sku', 'BRG034')
                ->orWhere('sku', 'like', 'RTG%')
                ->pluck('sku')
                ->toArray();

            $vendorKopi->barangs()->sync($skusKopi);
        }
    }
}
