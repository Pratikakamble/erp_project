<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run() :void
    {
        // Call the UserSeeder to seed the users
        $this->call(UserSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SalesOrderSeeder::class);

        // You can add other seeders here if needed, like:
        // $this->call(PostSeeder::class);
    }
}
