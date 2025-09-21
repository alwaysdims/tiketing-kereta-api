<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auth extends Model

{
    use HasFactory;
    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'nik',
        'jenis_kelamin',
        'role',
    ];

}
