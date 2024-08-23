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
            $table->foreignId('id_supplier')->references('id')->on('suppliers');
            $table->json('detail');
            $table->date('date_first');
            $table->date('date_last');
            $table->bigInteger('ppn_global')->default(0);
            $table->bigInteger('total_harga')->default(0);
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
