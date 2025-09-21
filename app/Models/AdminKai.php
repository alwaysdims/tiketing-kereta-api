<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminKai extends Model
{
    use HasFactory;

    protected $table = 'admin_kai';

    protected $fillable = [
        'id_user',
        'nama_admin',
        'jabatan',
        'no_hp',
        'alamat',
        'nip',
        'shift',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
