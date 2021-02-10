<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            CompanyProfile::create([
                'customers_number' => $faker->randomNumber(),
                'orders_number' => $faker->randomNumber(),
                'delivery_speed' => $faker->randomNumber(),
                'has_store' => $faker->boolean,
                'badge' => 'silver',
                'specialization' => 'electronics',
            ]);
        }
    }
}
