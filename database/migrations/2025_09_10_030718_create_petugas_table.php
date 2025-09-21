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
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('nama_petugas', 100);
            $table->string('nip', 50)->unique();
            $table->string('jabatan', 100); // Loket, Boarding, Kondektur, Masinis
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->enum('shift', ['pagi','siang','malam'])->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
