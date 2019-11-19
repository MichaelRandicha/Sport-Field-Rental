<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    public $timestamps = false;
	
    protected $fillable = [
        'user_id', 'lapangan_id',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function lapangan(){
        return $this->belongsTo('App\Lapangan');
    }
}
