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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('nama');
            $table->string('alamat1')->nullable();
            $table->string('alamat2')->nullable();
            $table->integer('penjualan_rata')->default(0);
            $table->integer('waktu_kunjungan')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->integer('stok_maksimum')->default(0);
            $table->smallInteger('is_ppn')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
