<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    protected $table = 'petugas';

    protected $fillable = [
        'id_user',
        'nama_petugas',
        'nip',
        'jabatan',
        'no_hp',
        'alamat',
        'shift',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
