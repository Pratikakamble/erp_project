<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('products')->insert([
            [
                'name' => 'Laptop',
                'sku' => 'LAP-001',
                'price' => 55000,
                'quantity' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Keyboard',
                'sku' => 'KBD-002',
                'price' => 1200,
                'quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mouse',
                'sku' => 'MSE-003',
                'price' => 500,
                'quantity' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monitor',
                'sku' => 'MON-004',
                'price' => 8000,
                'quantity' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Printer',
                'sku' => 'PRT-005',
                'price' => 15000,
                'quantity' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
