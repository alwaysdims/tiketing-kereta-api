<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'nik',
        'password',
        'jenis_kelamin',
        'role',
    ];

    public function adminKai()
    {
        return $this->hasOne(AdminKai::class, 'id_user');
    }

    public function penumpang()
    {
        return $this->hasOne(Penumpang::class, 'id_user');
    }
}
