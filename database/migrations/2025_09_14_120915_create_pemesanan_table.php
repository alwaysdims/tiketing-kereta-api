<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penumpang');
            $table->unsignedBigInteger('id_jadwal');
            $table->dateTime('tanggal_pesan');
            $table->decimal('total_bayar', 12, 2);
            $table->enum('status_bayar', ['pending', 'lunas','sudah bayar', 'batal']);
            $table->timestamps();

            $table->foreign('id_penumpang')->references('id')->on('penumpang')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('id')->on('jadwal_kereta')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pemesanan');
    }
};
