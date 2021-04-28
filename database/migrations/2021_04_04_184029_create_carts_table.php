<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->unsignedbigInteger('store');
            $table->foreign('store')->references('id')->on('stores');
            $table->unsignedbigInteger('product');
            $table->foreign('product')->references('id')->on('products');
            $table->unsignedbigInteger('customer');
            $table->foreign('customer')->references('id')->on('users');
            $table->integer('num');
            $table->float('total');
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
        Schema::dropIfExists('carts');
    }
}
