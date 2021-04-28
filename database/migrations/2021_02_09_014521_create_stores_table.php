<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->float('rating');
            $table->integer('ratings_number');
            $table->enum('categories', ['men', 'electric']);
            $table->timestamps();
            $table->unsignedbigInteger('owner');
            $table->foreign('s_owner')->references('id')->on('seller_profiles');
            $table->foreign('c_owner')->references('id')->on('company_profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
