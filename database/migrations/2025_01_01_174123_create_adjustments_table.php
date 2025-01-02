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
        Schema::create('adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_product')->references('id')->on('products');
            $table->string('nomor')->nullable();
            $table->string('nama');
            $table->bigInteger('stok_lama')->default(0);
            $table->bigInteger('stok_baru')->default(0);
            $table->bigInteger('selisih_qty')->default(0);
            $table->bigInteger('selisih_rupiah')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustments');
    }
};
