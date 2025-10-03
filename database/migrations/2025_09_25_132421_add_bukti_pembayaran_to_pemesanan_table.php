<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('status_bayar');
        });
    }

    public function down(): void {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });
    }
};
