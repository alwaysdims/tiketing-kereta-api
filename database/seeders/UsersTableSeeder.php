<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Insert Users
        $users = [
            [
                'username' => 'admin1',
                'email' => 'admin1@example.com',
                'password' => Hash::make('password123'),
                'nik' => '3201010101010001',
                'jenis_kelamin' => 'L',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'petugas1',
                'email' => 'petugas1@example.com',
                'password' => Hash::make('password123'),
                'nik' => '3201010101010002',
                'jenis_kelamin' => 'L',
                'role' => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'penumpang1',
                'email' => 'penumpang1@example.com',
                'password' => Hash::make('password123'),
                'nik' => '3201010101010003',
                'role' => 'penumpang',
                'jenis_kelamin' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        // Ambil ID yang baru dimasukkan
        $adminId = DB::table('users')->where('username', 'admin1')->value('id');
        $petugasId = DB::table('users')->where('username', 'petugas1')->value('id');
        $penumpangId = DB::table('users')->where('username', 'penumpang1')->value('id');

        // Insert Admin
        DB::table('admin_kai')->insert([
            'id_user' => $adminId,
            'nama_admin' => 'Budi Santoso',
            'nip' => 'ADM001',
            'jabatan' => 'Admin Sistem',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'shift' => 'pagi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Petugas
        DB::table('petugas')->insert([
            'id_user' => $petugasId,
            'nama_petugas' => 'Siti Aminah',
            'nip' => 'PTG001',
            'jabatan' => 'Loket',
            'no_hp' => '082345678901',
            'alamat' => 'Jl. Diponegoro No. 10, Bandung',
            'shift' => 'siang',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Penumpang
        DB::table('penumpang')->insert([
            'id_user' => $penumpangId,
            'nama_penumpang' => 'Andi Pratama',
            'tanggal_lahir' => '1998-05-12',
            'no_hp' => '083456789012',
            'alamat' => 'Jl. Sudirman No. 20, Yogyakarta',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
