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
            $table->foreignId('id_supplier')->references('id')->on('suppliers');
            $table->foreignId('id_unit')->references('id')->on('units');
            $table->foreignId('id_departemen')->references('id')->on('departemens');
            $table->string('kode');
            $table->string('kode_sumber')->nullable();
            $table->string('kode_alternatif')->nullable();
            $table->string('nama');
            $table->string('unit_beli');
            $table->string('unit_jual');
            $table->string('konversi');
            $table->bigInteger('harga_pokok');
            $table->bigInteger('harga_jual');
            $table->decimal('profit', 5, 2)->default(0);
            $table->bigInteger('merek')->nullable();
            $table->bigInteger('label')->nullable();
            $table->decimal('stok', 5, 2)->default(0);
            $table->decimal('diskon1', 5, 2)->default(0);
            $table->decimal('diskon2', 5, 2)->default(0);
            $table->decimal('diskon3', 5, 2)->default(0);
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
