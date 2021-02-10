<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            Order::create([
                'customer_name' => $faker->name,
                'seller_name' => $faker->name,
                'price' => $faker->randomNumber(),
                'status' => 'closed',
                'customer_id' => '3',
                'seller_id' => '2',
            ]);
        }
    }
}
