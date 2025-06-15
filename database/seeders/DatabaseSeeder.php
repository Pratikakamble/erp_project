<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run() :void
    {
        // Call the UserSeeder to seed the users
        $this->call(UserSeeder::class);

        // You can add other seeders here if needed, like:
        // $this->call(PostSeeder::class);
    }
}
