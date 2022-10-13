<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id('voucher_id');
            $table->string('nama_voucher');
            $table->integer('potongan');
            $table->unsignedBigInteger('minimal_pengambilan');
            $table->unsignedBigInteger('maksimal_pemotongan');
            $table->date('tanggal_berlaku');
            $table->date('tanggal_batas_berlaku');
            $table->timestampsTz($precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
