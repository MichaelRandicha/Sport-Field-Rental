<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRekeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekenings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lapangan_id');
            $table->foreign('lapangan_id')->references('id')->on('lapangans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('jenis_rekening', 7);
            $table->string('rekening', 15);
            $table->string('rekening_atas_nama');
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
        Schema::dropIfExists('rekenings');
    }
}
