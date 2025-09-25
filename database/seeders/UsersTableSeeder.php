<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Seed Users
        $users = [];
        for ($i = 1; $i <= 30; $i++) {
            $role = $i <= 10 ? 'admin' : ($i <= 20 ? 'petugas' : 'penumpang');
            $users[] = [
                'username' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'nik' => $faker->unique()->nik,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'role' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insert($users);

        // Get User IDs
        $adminIds = DB::table('users')->where('role', 'admin')->pluck('id')->toArray();
        $petugasIds = DB::table('users')->where('role', 'petugas')->pluck('id')->toArray();
        $penumpangIds = DB::table('users')->where('role', 'penumpang')->pluck('id')->toArray();

        // Seed Admin KAI
        $adminKai = [];
        foreach ($adminIds as $index => $id) {
            $adminKai[] = [
                'id_user' => $id,
                'nama_admin' => $faker->name,
                'nip' => 'ADM' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'jabatan' => $faker->randomElement(['Admin Sistem', 'Admin Jadwal', 'Admin Tiket']),
                'no_hp' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'shift' => $faker->randomElement(['pagi', 'siang', 'malam']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('admin_kai')->insert($adminKai);

        // Seed Petugas
        $petugas = [];
        foreach ($petugasIds as $index => $id) {
            $petugas[] = [
                'id_user' => $id,
                'nama_petugas' => $faker->name,
                'nip' => 'PTG' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'jabatan' => $faker->randomElement(['Loket', 'Boarding', 'Kondektur', 'Masinis']),
                'no_hp' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'shift' => $faker->randomElement(['pagi', 'siang', 'malam']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('petugas')->insert($petugas);

        // Seed Penumpang
        $penumpang = [];
        foreach ($penumpangIds as $id) {
            $penumpang[] = [
                'id_user' => $id,
                'nama_penumpang' => $faker->name,
                'tanggal_lahir' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
                'no_hp' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('penumpang')->insert($penumpang);

        // Seed Kereta
        $kereta = [];
        for ($i = 1; $i <= 75; $i++) {
            $kereta[] = [
                'kode_kereta' => 'KRT' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_kereta' => $faker->randomElement(['Argo Bromo', 'Gajayana', 'Bima', 'Taksaka', 'Jayabaya']) . ' ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('kereta')->insert($kereta);

        // Get Kereta IDs
        $keretaIds = DB::table('kereta')->pluck('id')->toArray();

        // Seed Gerbong
        $gerbong = [];
        foreach ($keretaIds as $keretaId) {
            for ($i = 1; $i <= 4; $i++) {
                $gerbong[] = [
                    'id_kereta' => $keretaId,
                    'kode_gerbong' => 'GRB' . str_pad($keretaId, 3, '0', STR_PAD_LEFT) . '-' . $i,
                    'no_gerbong' => $i,
                    'jumlah_kursi' => $faker->numberBetween(40, 80),
                    'kelas_gerbong' => $faker->randomElement(['ekonomi', 'bisnis', 'eksekutif', 'luxury']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('gerbong')->insert($gerbong);

        // Seed Stasiun (100 data)
        $stasiun = [];
        for ($i = 1; $i <= 100; $i++) {
            $namaKota = $faker->city;
            $stasiun[] = [
                'kode_stasiun' => 'ST' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_stasiun' => 'Stasiun ' . $namaKota,
                'kota' => $namaKota,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('stasiun')->insert($stasiun);


        // // Get Stasiun IDs
        // $stasiunIds = DB::table('stasiun')->pluck('id')->toArray();

        $faker = Faker::create('id_ID');

                // Ambil ID stasiun & kereta
                // Ambil ID stasiun & kereta
        $stasiunIds = DB::table('stasiun')->pluck('id')->toArray();
        $keretaIds = DB::table('kereta')->pluck('id')->toArray();

        $jadwalKereta = [];

        $startDate = now();
        $endDate = now()->addMonth();
        $currentDate = clone $startDate;

        // Hitung berapa per hari
        $totalHari = $startDate->diffInDays($endDate);
        $perHari = intdiv(500, $totalHari); // rata-rata
        $sisa = 500 % $totalHari;           // sisanya dibagi rata ke beberapa hari

        while ($currentDate->lt($endDate)) {
            $jumlahHariIni = $perHari;

            // kalau masih ada sisa, tambahkan 1 jadwal di beberapa hari pertama
            if ($sisa > 0) {
                $jumlahHariIni++;
                $sisa--;
            }

            for ($i = 0; $i < $jumlahHariIni; $i++) {
                $asal = $faker->randomElement($stasiunIds);
                $tujuan = $faker->randomElement(array_diff($stasiunIds, [$asal]));

                // Jam keberangkatan random di hari itu
                $jamKeberangkatan = $faker->dateTimeBetween(
                    $currentDate->copy()->startOfDay(),
                    $currentDate->copy()->endOfDay()
                );

                // Jam kedatangan = keberangkatan + durasi 2–12 jam
                $jamKedatangan = (clone $jamKeberangkatan)->modify('+' . $faker->numberBetween(2, 12) . ' hours');

                $jadwalKereta[] = [
                    'id_kereta' => $faker->randomElement($keretaIds),
                    'id_stasiun_asal' => $asal,
                    'id_stasiun_tujuan' => $tujuan,
                    'jam_keberangkatan' => $jamKeberangkatan,
                    'jam_kedatangan' => $jamKedatangan,
                    'harga' => $faker->numberBetween(100000, 500000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $currentDate->addDay();
        }

        // Insert sekali biar cepat
        DB::table('jadwal_kereta')->insert($jadwalKereta);

        }
    }

