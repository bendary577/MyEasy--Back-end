<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(SellerTableSeeder::class);
        $this->call(StoreTableSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(InvoiceTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(OrderTableSeeder::class);
    }
}
