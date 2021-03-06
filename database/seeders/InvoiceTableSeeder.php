<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceTableSeeder extends Seeder
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
            Invoice::create([
                'price' => $faker->randomNumber(),
                'expiration' => $faker->date(),
                'owner' => '1',
            ]);
        }
    }
}
