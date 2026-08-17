<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Kategori;
use App\Models\Produk;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admin & Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Administrator Luthfi',
                'role' => '1',
                'status' => 1,
                'hp' => '0812345678901',
                'password' => bcrypt('bsi06'),
            ]
        );

        $oke = User::updateOrCreate(
            ['email' => 'oke@gmail.com'],
            [
                'nama' => 'Admin Oke',
                'role' => '0',
                'status' => 1,
                'hp' => '0812345678902',
                'password' => bcrypt('bsi06'),
            ]
        );

        $staff = User::updateOrCreate(
            ['email' => 'luthfi@gmail.com'],
            [
                'nama' => 'Luthfi',
                'role' => '0',
                'status' => 1,
                'hp' => '081234567892',
                'password' => bcrypt('farhan123'),
            ]
        );

        // Sample Customer User
        $userCustomer = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'nama' => 'Customer Demo',
                'role' => '2',
                'status' => 1,
                'hp' => '081298765432',
                'password' => bcrypt('password123'),
            ]
        );

        Customer::firstOrCreate(
            ['user_id' => $userCustomer->id],
            [
                'alamat' => 'Jl. Margonda Raya No. 100, Pondok Cina, Beji',
                'pos' => '16424',
            ]
        );

        // 2. Kategori
        $katBrownies = Kategori::firstOrCreate(['nama_kategori' => 'Brownies']);
        $katCombro   = Kategori::firstOrCreate(['nama_kategori' => 'Combro']);
        $katDawet    = Kategori::firstOrCreate(['nama_kategori' => 'Dawet']);
        $katMochi    = Kategori::firstOrCreate(['nama_kategori' => 'Mochi']);
        $katWingko   = Kategori::firstOrCreate(['nama_kategori' => 'Wingko']);

        // 3. Sample Produk
        $produks = [
            [
                'kategori_id' => $katDawet->id,
                'user_id' => $admin->id,
                'status' => 1,
                'nama_produk' => 'Dawet Daun Singkong',
                'detail' => '<p>Minuman dawet segar khas tradisional dengan sensasi daun singkong yang nikmat dan menyegarkan.</p>',
                'harga' => 8000,
                'stok' => 50,
                'berat' => 1.0,
                'foto' => '20250408140127_67f4c9c724d7e.jpg',
            ],
            [
                'kategori_id' => $katCombro->id,
                'user_id' => $admin->id,
                'status' => 1,
                'nama_produk' => 'Combro frozen isi oncom + ikan cakalang',
                'detail' => '<p>Combro renyah gurih dengan isian oncom pedas dan suwiran ikan cakalang khas Nusantara.</p>',
                'harga' => 35000,
                'stok' => 50,
                'berat' => 1.0,
                'foto' => '20250408140233_67f4ca09c3549.jpg',
            ],
            [
                'kategori_id' => $katBrownies->id,
                'user_id' => $admin->id,
                'status' => 1,
                'nama_produk' => 'Brownies Singkong Cokelat Lumer',
                'detail' => '<p>Brownies lembut dengan cita rasa cokelat pekat khas dan bahan singkong berkualitas.</p>',
                'harga' => 28000,
                'stok' => 50,
                'berat' => 1.0,
                'foto' => '20250408140354_67f4ca5a6e6e9.jpg',
            ],
            [
                'kategori_id' => $katWingko->id,
                'user_id' => $admin->id,
                'status' => 1,
                'nama_produk' => 'Wingko Singkong Keju',
                'detail' => '<p>Kue wingko legit dengan perpaduan kelapa muda dan taburan keju gurih melimpah.</p>',
                'harga' => 28000,
                'stok' => 100,
                'berat' => 2.0,
                'foto' => '20250408140439_67f4ca87bfc4d.jpg',
            ],
            [
                'kategori_id' => $katMochi->id,
                'user_id' => $admin->id,
                'status' => 1,
                'nama_produk' => 'Mochi Singkong Coklat',
                'detail' => '<p>Mochi kenyal dengan isian coklat lumer yang manis dan legit di lidah.</p>',
                'harga' => 30000,
                'stok' => 50,
                'berat' => 1.0,
                'foto' => '20250408140707_67f4cb1b84c21.jpg',
            ],
            [
                'kategori_id' => $katMochi->id,
                'user_id' => $admin->id,
                'status' => 1,
                'nama_produk' => 'Mochi Singkong Keju',
                'detail' => '<p>Mochi lembut isi keju gurih dengan tekstur kenyal istimewa.</p>',
                'harga' => 30000,
                'stok' => 50,
                'berat' => 1.0,
                'foto' => '20250408140942_67f4cbb63294a.jpg',
            ],
        ];

        foreach ($produks as $p) {
            Produk::firstOrCreate(
                ['nama_produk' => $p['nama_produk']],
                $p
            );
        }
    }
}