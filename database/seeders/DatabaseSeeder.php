<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        // Data contoh tidak dijalankan di server produksi
        if (! app()->isProduction()) {
            $this->call(SampleDataSeeder::class);
        }
    }
}
