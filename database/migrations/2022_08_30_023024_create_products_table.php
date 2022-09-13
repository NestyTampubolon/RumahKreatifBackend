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
            $table->id('product_id');
            $table->unsignedBigInteger('merchant_id');
            $table->string('name');
            $table->string('price');
            $table->text('images');
            // $table->string('category');
            // $table->integer('stock');
            // $table->integer('sold');
            // $table->text('description');
            // $table->string('specification');
            // $table->string('color');
            // $table->string('cat_product');
            // $table->string('asal');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
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
