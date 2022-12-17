<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Site::insert([
            'name' => 'Mart Bangunan',
            'address' => 'Jl. Raya Kedungwuni No. 1, Kedungwuni, Kec. Kedungwuni, Kota Bandung, Jawa Barat 40257',
            'phone' => '+6281212121212',
            'email' => 'samplemail@gmail.com',
            'facebook' => 'https://www.facebook.com/martbangunan',
            'twitter' => 'https://www.twitter.com/martbangunan',
            'instagram' => 'https://www.instagram.com/martbangunan',
            'whatsapp' => 'https://wa.me/6281212121212',
            'description' => 'Martbangunan adalah sebuah website yang menyediakan produk bangunan berkualitas dengan harga terjangkau.',
            'logo' => 'logo.png',
            'favicon' => 'favicon.png',
            'about' => 'Martbangunan adalah sebuah website yang menyediakan pilihan produk bangunan berkualitas dengan harga terjangkau.',
            'sk' => 'Lorem ipsum dolor sit amet',
            'created_at' => now(),
        ]);
    }
}
