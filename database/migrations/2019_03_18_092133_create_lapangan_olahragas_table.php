<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLapanganOlahragasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lapangan_olahragas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lapangan_id');
            $table->foreign('lapangan_id')->references('id')->on('lapangans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->integer('harga_per_jam');
            $table->string('jenis_olahraga');
            $table->string('fasilitas');
            // $table->tinyInteger('discount')->default(0);
            $table->string('image')->nullable();
            $table->string('image_resized')->nullable();
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
        Schema::dropIfExists('lapangan_olahragas');
    }
}
