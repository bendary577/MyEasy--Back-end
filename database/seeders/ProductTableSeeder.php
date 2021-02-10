<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
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
            Product::create([
                'name' => $faker->name,
                'description' => $faker->text,
                'photo_path' => $faker->text,
                'available_number' => $faker->randomNumber(),
                'ratings_number' => $faker->randomNumber(),
                'price' => $faker->randomNumber(),
                'rating' => $faker->randomNumber(),
                'category' => 'electronics',
                'status' => 'new',
                'store' => '1',
                'customer_cart' => '4',
            ]);
        }
    }
}
