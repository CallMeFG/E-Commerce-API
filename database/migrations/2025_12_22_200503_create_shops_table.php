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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            //constraint: memastikan user_id ada di tabel users
            //cascadeondelete : jika user dihapus, maka shop  juga otomatis dihapus 
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            //dengan indexing
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            //untuk membekukan shop
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
