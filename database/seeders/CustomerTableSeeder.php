<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\SellerProfile;
use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
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
            CustomerProfile::create([
                'gender' => 'male',
                'orders_number' => $faker->randomNumber(),
                'birth_date' => $faker->date(),
            ]);
        }
    }
}
