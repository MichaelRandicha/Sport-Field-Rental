<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LapanganSchedule extends Model
{
	public $timestamps = false;
	
    protected $fillable = [
        'lapangan_id', 'hari_buka',
    ];
}
