<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lapangan_olahraga_id');
            $table->foreign('lapangan_olahraga_id')->references('id')->on('lapangan_olahragas')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('discount');
            $table->tinyInteger('dari_jam');
            $table->tinyInteger('sampai_jam');
            $table->date('dari_tanggal');
            $table->date('sampai_tanggal');
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
        Schema::dropIfExists('discounts');
    }
}
