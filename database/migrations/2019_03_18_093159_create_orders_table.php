<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('lapangan_olahraga_id');
            $table->foreign('lapangan_olahraga_id')->references('id')->on('lapangan_olahragas')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('rekening_id');
            $table->string('name')->nullable();
            $table->string('status');
            $table->string('payment_status');
            $table->integer('harga_per_jam');
            // $table->tinyInteger('discount');
            $table->date('tanggal_pesan');
            $table->tinyInteger('jam_pesan_start');
            $table->tinyInteger('jam_pesan_end');
            $table->string('identity_card_img')->nullable();
            $table->string('payment_img')->nullable();
            $table->timestamps();
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
