<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kereta extends Model
{
    use HasFactory;

    protected $table = 'kereta';

    protected $fillable = [
        'kode_kereta',
        'nama_kereta',

    ];

    public function gerbong()
    {
        return $this->hasMany(Gerbong::class, 'id_kereta');
    }

    public function jadwal(){
        return $this->hasMany(JadwalKereta::class,'id_kereta');
    }
}
