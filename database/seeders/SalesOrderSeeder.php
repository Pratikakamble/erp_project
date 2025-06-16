<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $products = Product::all();

        if ($products->count() === 0) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            DB::transaction(function () use ($faker, $products) {
                $customerName = $faker->name;
                $total = 0;

                $order = SalesOrder::create([
                    'customer_name' => $customerName,
                    'total' => 0, // temp
                ]);

                $selectedProducts = $products->random(rand(2, 4));

                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 5);
                    $price = $product->price;
                    $subtotal = $qty * $price;

                    SalesOrderItem::create([
                        'sales_order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);

                   // $product->decrement('quantity', $qty);
                    $total += $subtotal;
                }

                $order->update(['total' => $total]);
            });
        }
    }
}
