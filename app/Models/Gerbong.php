<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gerbong extends Model
{
    use HasFactory;

    protected $table = 'gerbong';

    protected $fillable = [
        'id_kereta',
        'kode_gerbong',
        'no_gerbong',
        'jumlah_kursi',
        'kelas_gerbong',
    ];

    public function kereta()
    {
        return $this->belongsTo(Kereta::class, 'id_kereta');
    }

    public function jadwalKereta()
    {
        return $this->hasMany(JadwalKereta::class, 'id_gerbong');
    }
}
