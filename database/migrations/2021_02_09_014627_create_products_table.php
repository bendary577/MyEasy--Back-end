<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('description');
            $table->string('photo_path');
            $table->integer('available_number');
            $table->integer('ratings_number')->default(0);
            $table->float('price');
            $table->float('rating')->default(0);
            $table->enum('category', ['electronics', 'sports']);
            $table->enum('status', ['new', 'used']);
            $table->timestamps();
            $table->unsignedbigInteger('store')->default(1);
            $table->foreign('store')->references('id')->on('stores');
            $table->unsignedbigInteger('customer_cart')->default(1);
            $table->foreign('customer_cart')->references('id')->on('customer_profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
