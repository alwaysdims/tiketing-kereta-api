<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gerbong', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kereta');
            $table->string('kode_gerbong', 20);
            $table->integer('no_gerbong');
            $table->integer('jumlah_kursi');
            $table->enum('kelas_gerbong', ['ekonomi', 'bisnis', 'eksekutif','luxury']);

            $table->timestamps();

            $table->foreign('id_kereta')->references('id')->on('kereta')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('gerbong');
    }
};
