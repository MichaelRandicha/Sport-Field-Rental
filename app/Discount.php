<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'lapangan_olahraga_id', 'discount', 'dari_jam', 'sampai_jam', 'dari_tanggal', 'sampai_tanggal'
    ];

    public function lapanganOlahraga(){
    	return $this->belongsTo('App\LapanganOlahraga');
    }

    public function order(){
    	return $this->hasMany('App\Order');
    }
}
