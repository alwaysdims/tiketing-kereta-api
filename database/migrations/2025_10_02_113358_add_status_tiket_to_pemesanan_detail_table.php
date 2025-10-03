<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan_detail', function (Blueprint $table) {
            $table->enum('status_tiket', ['aktif', 'batal', 'selesai'])
                  ->default('aktif')
                  ->after('kode_barcode');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan_detail', function (Blueprint $table) {
            $table->dropColumn('status_tiket');
        });
    }
};
