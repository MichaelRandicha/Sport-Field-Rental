<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('orders', function(Blueprint $table){
        //     $table->string('jenis_rekening', 7)->after('jam_pesan_end');
        //     $table->string('rekening', 15)->after('jam_pesan_end');
        //     $table->string('rekening_atas_nama')->after('jam_pesan_end');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('orders', function(Blueprint $table){
            $table->dropColumn('jenis_rekening', 'rekening', 'rekening_atas_nama');
        });*/
    }
}
