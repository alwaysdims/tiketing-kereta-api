<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pemesanan_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesan');
            $table->unsignedBigInteger('id_gerbong');
            $table->string('no_kursi', 10);
            $table->string('nama_penumpang', 100);
            $table->string('kode_barcode', 100);
            $table->timestamps();

            $table->foreign('id_pesan')->references('id')->on('pemesanan')->onDelete('cascade');
            $table->foreign('id_gerbong')->references('id')->on('gerbong')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pemesanan_detail');
    }
};
