<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_product_specifications', function (Blueprint $table) {
            $table->id('purchase_product_specification_id');
            $table->unsignedBigInteger('product_purchase_id');
            $table->unsignedBigInteger('product_specification_id');
            
            $table->foreign('product_purchase_id')->references('product_purchase_id')->on('product_purchases');
            $table->foreign('product_specification_id')->references('product_specification_id')->on('product_specifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_product_specifications');
    }
}
