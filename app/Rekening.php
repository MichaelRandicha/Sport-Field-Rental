<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    protected $fillable = [
        'lapangan_id', 'jenis_rekening', 'rekening', 'rekening_atas_nama'
    ];

    public static $jenisRekening = [
        'BCA', 'Mandiri', 'BRI', 'BNI', 'CIMB'
    ];

    public function lapangan(){
    	return $this->belongsTo('App\Lapangan');
    }

    public function order(){
    	return $this->hasMany('App\Order');
    }
}
