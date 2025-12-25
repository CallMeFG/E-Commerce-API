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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Pembeli
            // menyimpan nomor invoice unik agar fleksibel, misal: INV-20231001-001
            $table->string('invoice_number')->unique();
            $table->decimal('total_price', 12, 2);
            // status order dengan indexing untuk mempercepat query berdasarkan status
            $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'cancelled'])->default('pending')->index();

            $table->timestamps();
        });
        Schema::create('order_items',function(Blueprint $table){
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            //simpan harga saat beli, agar jika harga produk berubah tidak mempengaruhi histori order
            $table->decimal('price_at_purchase',12,2); // harga saat pembelian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
