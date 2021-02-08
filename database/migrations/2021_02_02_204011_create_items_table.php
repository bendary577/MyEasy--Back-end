<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('description');
            $table->string('photo_path');
            $table->integer('available_number');
            $table->integer('ratings_number');
            $table->float('price');
            $table->float('rating');
            $table->enum('category', ['electronic', 'sports']);
            $table->enum('status', ['new', 'used']);
            $table->timestamps();
            $table->unsignedbigInteger('store');
            $table->foreign('store')->references('id')->on('stores');
            $table->unsignedbigInteger('customer_cart');
            $table->foreign('customer_cart')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
