<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id')->unique();          // referensi internal kita
            $table->string('no_invoice_jual', 50)->nullable();      // opsional, link ke nota_tagihan_penjualan
            $table->string('xendit_invoice_id')->nullable()->unique(); // "id" dari response Xendit
            $table->string('external_id')->nullable();          // external_id yang dikirim ke Xendit
            $table->string('payer_email')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->string('status')->default('PENDING');       // PENDING, PAID, SETTLED, EXPIRED, FAILED
            $table->text('invoice_url')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_channel')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('raw_response')->nullable();           // simpan mentah response Xendit
            $table->timestamps();

            $table->foreign('no_invoice_jual')
                ->references('no_invoice_jual')
                ->on('nota_tagihan_penjualan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
