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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();

            // Siapa yang melakukan aktivitas (opsional jika multi-user)
            $table->unsignedBigInteger('user_id')->nullable();

            // Jenis aktivitas (misal: login, create, update, delete, export, dll)
            $table->string('aktivitas', 100);

            // Detail tambahan (misal: request, perubahan sebelum/sesudah)
            $table->text('deskripsi')->nullable();

            // IP address (untuk tracking asal user)
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            // Relasi ke tabel users (kalau ada)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
