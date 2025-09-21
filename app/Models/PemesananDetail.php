<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananDetail extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_detail';

    protected $fillable = [
        'id_pesan',
        'id_gerbong',
        'no_kursi',
        'nama_penumpang',
        'kode_barcode',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pesan');
    }

    public function gerbong()
    {
        return $this->belongsTo(Gerbong::class, 'id_gerbong');
    }
}
