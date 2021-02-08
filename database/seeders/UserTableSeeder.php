<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        User::truncate();
        $faker = \Faker\Factory::create();

        $password = Hash::make('toptal');

        User::create([
            'first_name' => 'mohamed',
            'second_name' => 'bendary',
            'email' => 'admin@test.com',
            'password' => $password,
            'phone_number' => '01232323',
            'address' => 'asdasdasdasd',
            'zipcode' => '1234',
            'photo_path' => 'asdasdasdasd',
            'bio' => '12asdaasdasdasdsdasd34',
        ]);


        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => $faker->name,
                'second_name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
            ]);
        }
    }
}
