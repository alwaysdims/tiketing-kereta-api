<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';

    protected $fillable = [
        'id_penumpang',
        'id_jadwal',
        'tanggal_pesan',
        'total_bayar',
        'status_bayar',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Penumpang::class, 'id_penumpang');
    }

    public function jadwalKereta()
    {
        return $this->belongsTo(JadwalKereta::class, 'id_jadwal');
    }

    public function detail()
    {
        return $this->hasMany(PemesananDetail::class, 'id_pesan');
    }
}
