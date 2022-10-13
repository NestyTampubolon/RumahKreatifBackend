<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('purchase_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('checkout_id');
            $table->string('alamat_purchase')->nullable();
            $table->string('status_pembelian');
            $table->string('no_resi')->nullable();
            $table->timestampsTz($precision = 0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('checkout_id')->references('checkout_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
