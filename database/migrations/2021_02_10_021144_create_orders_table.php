<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("customer_name");
            $table->string("seller_name");
            $table->float("price");
            $table->enum("status", ['open', 'on_way', 'delivered', 'closed']);
            $table->boolean("customer_confirm")->default(0);
            $table->boolean("seller_confirm")->default(0);
            $table->timestamps();
            $table->unsignedbigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customer_profiles');
            $table->unsignedbigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('seller_profiles');
            $table->unsignedbigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
