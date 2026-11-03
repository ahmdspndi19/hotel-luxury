<?php

namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // LocationSeeder berisi data palsu dari factory: hanya untuk lokal/staging, jangan di production
        if (! app()->isProduction()) {
            $this->call(LocationSeeder::class);
        }
    }
}
