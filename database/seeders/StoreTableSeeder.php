<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {

        $faker = \Faker\Factory::create();
        Store::create([
            'name' => $faker->sentence,
            'owner' => '11',
        ]);
    }
}
