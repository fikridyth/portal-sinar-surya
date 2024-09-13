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
        Schema::create('hutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_supplier')->references('id')->on('suppliers');
            $table->string('nomor_po');
            $table->string('nomor_receive')->nullable();
            $table->string('nomor_bukti')->nullable();
            $table->date('date');
            $table->bigInteger('total')->default(0);
            $table->bigInteger('ppn')->default(0);
            $table->bigInteger('grand_total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutangs');
    }
};
