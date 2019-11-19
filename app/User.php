<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'created_by_id', 'timezone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function IsPO(){
        return $this->role_id == 1;
    }

    public function IsPL(){
        return $this->role_id == 2;
    }

    public function IsCS(){
        return $this->role_id == 3;
    }

    public function created_by(){
        return $this->belongsTo('App\User');
    }

    public function creates(){
        return $this->hasMany('App\User', 'created_by_id');
    }

    public function lapangan(){
        return $this->hasMany('App\Lapangan');
    }

    public function services(){
        return $this->hasMany('App\CustomerService', 'user_id');
    }

    public function serve(){
        return $this->belongsToMany('App\Lapangan', 'customer_services', 'user_id', 'lapangan_id');
    }

    public function order(){
        return $this->hasMany('App\Order');
    }

    public function review(){
        return $this->hasMany('App\Review');
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

    public function TipeUser(){
        if($this->isPO()){
            return "Penggemar Olahraga";
        }elseif($this->isPL()){
            return "Pemilik Lapangan";
        }elseif($this->isCS()){
            return "Customer Service";
        }
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
            abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) || 
        abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles)
    {
        return null !== $this->role()->whereIn('name', $roles)->first();
    }

    public function hasRole($role)
    {
        return null !== $this->role()->where('name', $role)->first();
    }
}
