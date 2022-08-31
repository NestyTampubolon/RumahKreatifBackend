<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('nama event');
            $table->date('tgl_awal');
            $table->date('tgl_akhirl');
            $table->string('lokasi');
            $table->string('foto');
            $table->string('status');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('kabupaten_id');
            $table->text('deskripsi');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('users');
            $table->foreign('kabupaten_id')->references('id')->on('kabupatens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
