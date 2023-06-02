<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Class UsersSeeder.
 */
class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run(): void
    {
        $products = Product::factory(15)
            ->create();

        Client::factory(20)
            ->create()
            ->each(function ($client) use ($products) {
                Order::factory(rand(2, 5))
                    ->create([
                        'client_id' => $client->id,
                    ])->each(function ($order) use ($products) {
                        $order->products()
                            ->attach($products->random(3));
                    });
            });

    }
}
