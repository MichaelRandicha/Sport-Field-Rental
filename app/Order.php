<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public static $timeLimit = 1;

    protected $fillable = [
        'user_id', 'lapangan_olahraga_id', 'rekening_id', 'discount_id', 'status', 'payment_status', 'harga_per_jam', 'discount', 'tanggal_pesan', 'jam_pesan_start', 'jam_pesan_end', 'identity_card_img', 'payment_img'
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
    ];

    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function olahraga(){
    	return $this->belongsTo('App\LapanganOlahraga', 'lapangan_olahraga_id');
    }

    public function length(){
    	return $this->jam_pesan_end - $this->jam_pesan_start;
    }

    public function discount(){
        return $this->belongsTo('App\Discount');
    }

    public function rekening(){
        return $this->belongsTo('App\Rekening');
    }

    public function getICPathAttribute(){
        if($this->identity_card_img == null){
            return asset('assets/images/user/image-default.png');
        }else{
            return asset('storage/images/user/pembayaran/identity_card_img/'.$this->identity_card_img);
        }
        return "";
    }

    public function getPPathAttribute(){
        if($this->payment_img == null){
            return asset('assets/images/user/image-default.png');
        }else{
            return asset('storage/images/user/pembayaran/payment_img/'.$this->payment_img);
        }
    }
}
