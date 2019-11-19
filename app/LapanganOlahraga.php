<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LapanganOlahraga extends Model
{
	protected $fillable = [
        'lapangan_id', 'name', 'harga_per_jam', 'jenis_olahraga', 'fasilitas', 'discount', 'image', 'image_resized'
    ];

    public static $jenisOlahraga = [
        'Basket', 'Bulu Tangkis', 'Futsal', 'Sepak Bola', 'Tenis Meja', 'Tenis', 'Voli'
    ];

    public function lapangan(){
    	return $this->belongsTo('App\Lapangan');
    }

    public function discount(){
        return $this->hasMany('App\Discount');
    }

    public function getDiskonAttribute(){
        if($this->discount() == null)
            return 0;
        $now = Carbon::now();
        $discounts = $this->discount->where('dari_tanggal', '<=', $now->toDateString())->where('sampai_tanggal', '>=', $now->toDateString());
        foreach ($discounts as $discount) {
            $from = Carbon::parse($discount->dari_tanggal);
            $from->hour = $discount->dari_jam;
            
            $until = Carbon::parse($discount->sampai_tanggal);
            $until->hour = $discount->sampai_jam;
            if($until->hour == 0)
                if($now >= $from && $now <= $until && $now->hour >= $from->hour)
                    return $discount;
            else
                if($now >= $from && $now <= $until && $now->hour >= $from->hour && $now->hour < $until->hour)
                return $discount;
            
        }
        return 0;
    }

    public function review(){
    	return $this->hasMany('App\Review');
    }

    public function order(){
        return $this->hasMany('App\Order');
    }


    public function getReviewCountAttribute(){
        return $this->review() ? $this->review()->count() : 0;
    }

    public function ratingTotal(){
        $count = 0;
        foreach($this->review as $review){
            $count += $review->rating;
        }
        return $count;
    }

    public function isBooked($date, int $hour){
        $order = $this->order()->whereNotIn('payment_status', ['canceled', 'denied'])->whereDate('tanggal_pesan', $date->toDateString())->where('jam_pesan_start', '<=', $hour)->where('jam_pesan_end', '>', $hour)->get();
        return $order->count() > 0;
    }

    public function isOrderStart($date, int $hour){
        $order = $this->order()->whereNotIn('payment_status', ['canceled', 'denied'])->whereDate('tanggal_pesan', $date->toDateString())->where('jam_pesan_start', $hour)->get();
        return $order->count() > 0;
    }

    public function OrderAt($date, int $hour){
        $order = $this->order()->whereNotIn('payment_status', ['canceled', 'denied'])->whereDate('tanggal_pesan', $date->toDateString())->where('jam_pesan_start', $hour)->get()->first();

        return $order;
    }

    public function getRatingAttribute(){
        $rating = $this->reviewCount > 0 ? round(($this->ratingTotal() / $this->reviewCount) * 2) / 2 : 0;
        return $rating;
    }

    public function getRealRatingAttribute(){
        return $this->reviewCount > 0 ? $this->ratingTotal() / $this->reviewCount : 0;
    }

    public function getImgAttribute(){
        if($this->image == null){
            return 'https://fakeimg.pl/600x400/d3d3d3/333333/?text=No Image&font=lobster';
        }else{
            return asset('storage/images/lapangan/olahraga/'.$this->image);
        }
    }

    public function getImgResizedAttribute(){
        if($this->image_resized == null){
            return 'https://fakeimg.pl/400x300/d3d3d3/333333/?text=No Image&font=lobster';
        }else{
            return asset('storage/images/lapangan/olahraga/'.$this->image_resized);
        }
    }
}
