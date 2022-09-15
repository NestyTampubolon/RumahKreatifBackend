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
            $table->unsignedBigInteger('category_id');
            $table->string('product_name');
            $table->text('product_description');
            $table->string('price');
            $table->string('product_image');
            // $table->string('category');
            // $table->integer('stock');
            // $table->integer('sold');
            // $table->text('description');
            // $table->string('specification');
            // $table->string('color');
            // $table->string('cat_product');
            // $table->string('asal');
            $table->timestamps();

            $table->foreign('merchant_id')->references('merchant_id')->on('merchants');
            $table->foreign('category_id')->references('category_id')->on('product_categories');
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
