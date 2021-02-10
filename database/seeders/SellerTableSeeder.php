<?php

namespace Database\Seeders;

use App\Models\SellerProfile;
use App\Models\Store;
use Illuminate\Database\Seeder;

class SellerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        SellerProfile::create([
            'id' => '11',
            'customers_number' => $faker->sentence,
            'customers_number' => $faker->paragraph,
            'delivery_speed' => $faker->randomNumber(),
            'has_store' => $faker->boolean,
            'birth_date' => $faker->date(),
        ]);
        for ($i = 0; $i < 50; $i++) {
            SellerProfile::create([
                'id' => $faker->randomNumber(),
                'customers_number' => $faker->sentence,
                'customers_number' => $faker->paragraph,
                'delivery_speed' => $faker->randomNumber(),
                'has_store' => $faker->boolean,
                'birth_date' => $faker->date(),
            ]);
        }
    }

}
