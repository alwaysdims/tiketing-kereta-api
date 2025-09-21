<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKereta extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kereta';

    protected $fillable = [
        'id_gerbong',
        'id_stasiun_asal',
        'id_stasiun_tujuan',
        'jam_keberangkatan',
        'jam_kedatangan',
        'harga',
    ];

    public function gerbong()
    {
        return $this->belongsTo(Gerbong::class, 'id_gerbong');
    }

    public function stasiunAsal()
    {
        return $this->belongsTo(Stasiun::class, 'id_stasiun_asal');
    }

    public function stasiunTujuan()
    {
        return $this->belongsTo(Stasiun::class, 'id_stasiun_tujuan');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_jadwal');
    }
}
