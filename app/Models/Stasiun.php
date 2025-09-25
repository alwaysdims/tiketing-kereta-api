<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stasiun extends Model
{
    use HasFactory;

    protected $table = 'stasiun';

    protected $fillable = [
        'kode_stasiun',
        'nama_stasiun',
        'kota',
    ];

    public function jadwalAsal()
    {
        return $this->hasMany(JadwalKereta::class, 'id_stasiun_asal');
    }

    public function jadwalTujuan()
    {
        return $this->hasMany(JadwalKereta::class, 'id_stasiun_tujuan');
    }
}
