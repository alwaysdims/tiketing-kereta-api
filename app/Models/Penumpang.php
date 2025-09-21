<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    use HasFactory;

    protected $table = 'penumpang';

    protected $fillable = [
        'id_user',
        'nama_penumpang',
        'no_hp',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_penumpang');
    }
}
