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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            //relasi ke shop
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            //menggunakan decimal untuk uang agar presisi akurat (dengan format 12 digit, dengan 2 digit belakang koma)
            $table->decimal('price',12,2);
            $table->integer('stock')->default(0);
            //menambahkan index pada kolom price untuk mempercepat query berdasarkan harga
            $table->index('price');
            //produk yang sudah dihapus tidak langsung hilang dari database (hanya status dimunculkan atau tidak)
            //untuk riwayat produk yang dihapus
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
