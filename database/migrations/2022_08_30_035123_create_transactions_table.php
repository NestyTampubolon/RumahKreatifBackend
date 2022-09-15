<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('merchant_id');
            $table->string('shipping_number');
            $table->string('courier');
            $table->string('address');
            $table->string('status');
            $table->integer('confirm_user');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('merchant_id')->references('merchant_id')->on('merchants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
