<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
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
            User::create([
                'first_name' => $faker->name,
                'second_name' => $faker->name,
                'email' => $faker->email,
                'password' => $faker->password,
            ]);
        }
    }
}
