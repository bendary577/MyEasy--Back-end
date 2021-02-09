<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string("haha");
            $table->integer('customers_number');
            $table->integer('orders_number');
            $table->float('delivery_speed');
            $table->boolean('has_store')->default(0);
            $table->enum('badge', ['golde', 'silver', 'bronze']);
            $table->enum('specilization', ['electronics', 'sports']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_profiles');
    }
}
