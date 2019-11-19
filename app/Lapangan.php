<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
	protected $fillable = [
        'user_id', 'name', 'location', 'no_telepon', 'image', 'image_resized', 'jam_buka', 'jam_tutup'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function schedule(){
        return $this->hasMany('App\LapanganSchedule');
    }

    public function olahraga(){
        return $this->hasMany('App\LapanganOlahraga');
    }

    public function rekening(){
        return $this->hasMany('App\Rekening');
    }

    public function getRekeningTerbaruAttribute(){
        return $this->rekening()->latest()->first();
    }

    public function cs(){
        return $this->belongsToMany('App\User', 'customer_services', 'lapangan_id', 'user_id');
    }

    public function order(){
        return $this->hasManyThrough('App\Order', 'App\LapanganOlahraga');
    }

    public function jenisOlahraga(){
        $olahragas = $this->olahraga()->groupBy('jenis_olahraga')->orderBy('id')->pluck('jenis_olahraga')->toArray();
        return $olahragas;
    }

    public function getReviewCountAttribute(){
        $count = 0;
        foreach($this->olahraga as $olahraga){
            $count += $olahraga->review() ? $olahraga->review()->count() : 0;
        }
        return $count;
    }

    public function ratingTotal(){
        $count = 0;
        foreach($this->olahraga as $olahraga){
            foreach($olahraga->review as $review){
                $count += $review->rating;
            }
        }
        return $count;
    }

    public function getRatingAttribute(){
        $rating = $this->reviewCount > 0 ? round(($this->ratingTotal() / $this->reviewCount) * 2) / 2 : 0;
        return $rating;
    }

    public function getRealRatingAttribute(){
        return $this->reviewCount > 0 ? $this->ratingTotal() / $this->reviewCount : 0;
    }

    public function getHariBukaAttribute(){
        $hb = $this->schedule()->orderBy('hari_buka')->get();
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        if($hb->count() == 1){
            return $days[$hb->first()->hari_buka];
        }elseif($hb->count() > 0){
            if($hb->first()->hari_buka == 0){
                $hari_buka = $days[$hb->first()->hari_buka + 1];
                $hb->shift();
                $minggu = true;
            }else{
                $hari_buka = $days[$hb->first()->hari_buka];
            }
            foreach ($hb as $hari) {
                if($hari != $hb->first()){
                    $hari_buka = $hari_buka.', '.$days[$hari->hari_buka];
                }
            }
            if($minggu){
                $hari_buka = $hari_buka.', '.$days[0];
            }
            return $hari_buka;
        }else{
            return '';
        }
    }

    public function DayOfWeek($i){
        $hb = $this->schedule()->orderBy('hari_buka')->get();
        return $hb->where('hari_buka', $i)->count() > 0;
    }

    public function getTimeDiffAttribute(){
        $time = Carbon::now();
        $timezone = Carbon::now()->setTimezone($this->timezone);
        return abs($time->hour - $timezone->hour);
    }

    public function getZoneAttribute(){
        $time = Carbon::now();
        $timezone = Carbon::now()->setTimezone($this->timezone);
        $diff = abs($time->hour - $timezone->hour);
        if($diff == 0){
            return "WIB";
        }elseif($diff == 1){
            return "WITA";
        }else{
            return "WIT";
        }
        return "";
    }

    public function getBukaAttribute(){
        $jam_buka = new Carbon();
        $jam_buka->hour = $this->jam_buka;
        $jam_buka->minute = 0;
        return $jam_buka->format('H:i');
    }

    public function getTutupAttribute(){
        $jam_tutup = new Carbon();
        $jam_tutup->hour = $this->jam_tutup;        
        $jam_tutup->minute = 0;
        return $jam_tutup->format('H:i');
    }

    public function getImgAttribute(){
        if($this->image == null){
            return 'https://fakeimg.pl/600x400/d3d3d3/333333/?text=No Image&font=lobster';
        }else{
            return asset('storage/images/lapangan/'.$this->image);
        }
    }

    public function getImgResizedAttribute(){
        if($this->image_resized == null){
            return 'https://fakeimg.pl/400x300/d3d3d3/333333/?text=No Image&font=lobster';
        }else{
            return asset('storage/images/lapangan/'.$this->image_resized);
        }
    }

    public function Hari($hari){
        return $this->schedule()->where('hari_buka', $hari)->get()->count() != 0;
    }
}
