<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGambarToMBarangTable extends Migration
{
    public function up()
    {
        Schema::table('m_barang', function (Blueprint $table) {
            $table->string('gambar', 255)->nullable()->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('m_barang', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
}
