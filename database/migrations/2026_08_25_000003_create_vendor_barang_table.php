<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_barang', function (Blueprint $table) {
            $table->id();
            $table->integer('id_vendor');
            $table->string('sku', 50);
            $table->decimal('harga_beli', 15, 2)->nullable()->comment('Harga beli khusus dari vendor ini (opsional)');
            $table->timestamps();

            $table->unique(['id_vendor', 'sku']);
            $table->foreign('id_vendor')->references('id_vendor')->on('m_vendor')->onDelete('cascade');
            $table->foreign('sku')->references('sku')->on('m_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_barang');
    }
};
