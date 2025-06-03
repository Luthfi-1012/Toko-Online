<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator Abni',
            'email' => 'admin@gmail.com',
            'role' => '1',
            'status' => 1,
            'hp' => '0812345678901',
            'password' => bcrypt('bsi06'),
        ]);
          #untuk record berikutnya silahkan, beri nilai berbeda pada nilai: nama, email, hp dengan 
          #nilai masing-masing anggota kelompok 
        User::create([ 
            'nama' => 'niee', 
            'email' => 'abni@gmail.com', 
            'role' => '0', 
            'status' => 1, 
            'hp' => '081234567892', 
            'password' => bcrypt('farhan123'), 
        ]); 
        #data kategori
        Kategori::create([
            'nama_kategori' => 'Brownies',
            ]);
            Kategori::create([
            'nama_kategori' => 'Combro',
            ]);
            Kategori::create([
            'nama_kategori' => 'Dawet',
            ]);
            Kategori::create([
            'nama_kategori' => 'Mochi',
            ]);
            Kategori::create([
            'nama_kategori' => 'Wingko',
            ]);
    }
}