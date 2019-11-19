<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLapanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('lapangans', function(Blueprint $table){
        //     $table->string('jenis_rekening', 7)->after('jam_tutup');
        //     $table->string('rekening', 15)->after('jam_tutup');
        //     $table->string('rekening_atas_nama')->after('jam_tutup');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('lapangans', function(Blueprint $table){
        //     $table->dropColumn('jenis_rekening', 'rekening', 'rekening_atas_nama');
        // });
    }
}
