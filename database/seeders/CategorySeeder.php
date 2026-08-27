<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Laptop & Komputer', 'description' => 'Perangkat laptop, PC desktop, dan All-in-One.'],
            ['name' => 'Peripheral & Aksesoris', 'description' => 'Mouse, keyboard, webcam, headset, dan aksesoris.'],
            ['name' => 'Komponen & Storage', 'description' => 'RAM, SSD, Harddisk, Power Supply, dan komponen PC.'],
            ['name' => 'Gadget & Smartphone', 'description' => 'Smartphone, tablet, smartwatch, dan aksesoris mobile.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // Map existing products to sample categories
        $catLaptop = Category::where('name', 'Laptop & Komputer')->first();
        $catPeripheral = Category::where('name', 'Peripheral & Aksesoris')->first();
        $catStorage = Category::where('name', 'Komponen & Storage')->first();
        $catGadget = Category::where('name', 'Gadget & Smartphone')->first();

        if ($catLaptop) {
            DB::table('m_barang')->whereIn('sku', ['BRG001', 'BRG002'])->update(['category_id' => $catLaptop->id]);
        }
        if ($catPeripheral) {
            DB::table('m_barang')->whereIn('sku', ['BRG003', 'BRG004', 'BRG005', 'BRG006', 'BRG012', 'BRG013'])->update(['category_id' => $catPeripheral->id]);
        }
        if ($catStorage) {
            DB::table('m_barang')->whereIn('sku', ['BRG007', 'BRG008', 'BRG009', 'BRG010'])->update(['category_id' => $catStorage->id]);
        }
        if ($catGadget) {
            DB::table('m_barang')->whereIn('sku', ['BRG015', 'BRG021', 'BRG022', 'BRG023'])->update(['category_id' => $catGadget->id]);
        }
    }
}
