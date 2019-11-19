<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'lapangan_olahraga_id', 'reply_to_id', 'review', 'rating'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user(){
    	return $this->belongsTo('App\User');
    }

	public function olahraga(){
    	return $this->belongsTo('App\LapanganOlahraga', 'lapangan_olahraga_id');
    }

    // public function reply_to(){
    // 	return $this->belongsTo('App\Review');
    // }

    // public function reply(){
    //     return $this->hasOne('App\Review', 'reply_to_id');
    // }
}
