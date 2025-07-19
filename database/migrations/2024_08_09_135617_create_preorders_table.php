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
        Schema::create('preorders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_po');
            $table->string('nomor_receive')->nullable();
            $table->foreignId('id_supplier')->references('id')->on('suppliers');
            $table->json('detail')->nullable();
            $table->date('date_first')->nullable();
            $table->date('date_last')->nullable();
            $table->bigInteger('total_harga')->default(0);
            $table->bigInteger('ppn_global')->default(0);
            $table->bigInteger('grand_total')->default(0);
            $table->bigInteger('diskon_global')->default(0);
            $table->smallInteger('is_cetak')->nullable();
            $table->smallInteger('is_receive')->nullable();
            $table->smallInteger('is_pay')->nullable();
            $table->string('receive_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preorders');
    }
};
