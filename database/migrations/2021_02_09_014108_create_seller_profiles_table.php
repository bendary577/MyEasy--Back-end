<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('customers_number');
            $table->integer('customers_number');
            $table->float('delivery_speed');
            $table->boolean('has_store')->default(0);
            $table->date('birth_date');
            $table->enum('gender', ['mail', 'femail']);
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
        Schema::dropIfExists('seller_profiles');
    }
}
