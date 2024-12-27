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
        Schema::create('harga_sementaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_supplier')->references('id')->on('suppliers');
            $table->foreignId('id_product')->references('id')->on('products');
            $table->string('nomor')->nullable();
            $table->string('nama');
            $table->bigInteger('harga_lama');
            $table->bigInteger('harga_pokok');
            $table->decimal('profit_pokok', 10, 2)->default(0);
            $table->bigInteger('harga_jual');
            $table->decimal('profit_jual', 10, 2)->default(0);
            $table->bigInteger('harga_sementara');
            $table->decimal('naik', 10, 2)->default(0);
            $table->date('date_first')->nullable();
            $table->date('date_last')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_sementaras');
    }
};
