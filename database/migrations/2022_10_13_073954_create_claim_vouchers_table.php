<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_vouchers', function (Blueprint $table) {
            $table->id('claim_voucher_id');
            $table->unsignedBigInteger('checkout_id');
            $table->timestampsTz($precision = 0);
            
            $table->foreign('checkout_id')->references('checkout_id')->on('checkouts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_vouchers');
    }
}
