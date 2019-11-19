<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function(Blueprint $table){
            // $table->dropColumn('discount_id');
            // $table->dropColumn(['jenis_rekening', 'rekening', 'rekening_atas_nama']);
            $table->unsignedBigInteger('discount_id')->nullable()->after('lapangan_olahraga_id');
            $table->foreign('discount_id')->references('id')->on('discounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
