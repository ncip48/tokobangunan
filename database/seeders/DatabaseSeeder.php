<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            KategoriSeeder::class,
            MerkSeeder::class,
            ProdukSeeder::class,
            TokoSeeder::class,
            AlamatSeeder::class,
            BankSeeder::class,
            AdminUserSeeder::class,
            SiteSeeder::class,
        ]);
    }
}
