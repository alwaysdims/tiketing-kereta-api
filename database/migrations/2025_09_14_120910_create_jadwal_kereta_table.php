<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jadwal_kereta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kereta');
            $table->unsignedBigInteger('id_stasiun_asal');
            $table->unsignedBigInteger('id_stasiun_tujuan');
            $table->dateTime('jam_keberangkatan');
            $table->dateTime('jam_kedatangan');
            $table->decimal('harga', 12, 2);
            $table->timestamps();

            $table->foreign('id_kereta')->references('id')->on('kereta')->onDelete('cascade');
            $table->foreign('id_stasiun_asal')->references('id')->on('stasiun')->onDelete('cascade');
            $table->foreign('id_stasiun_tujuan')->references('id')->on('stasiun')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('jadwal_kereta');
    }
};
