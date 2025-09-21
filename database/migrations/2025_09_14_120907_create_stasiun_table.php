<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stasiun', function (Blueprint $table) {
            $table->id();
            $table->string('kode_stasiun', 20)->unique();
            $table->string('nama_stasiun', 100);
            $table->string('kota', 100);
        });
    }

    public function down(): void {
        Schema::dropIfExists('stasiun');
    }
};
