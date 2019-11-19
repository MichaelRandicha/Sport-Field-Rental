<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\Lapangan;
use App\LapanganOlahraga;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DaftarPemesananController extends Controller
{
    public function index(Lapangan $lapangan)
    {
    	Auth::user()->authorizeRoles('CS');
    	if(Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
    		return abort(401);
    	}
        $time = Carbon::now();
    	$orders = $lapangan->order()->where('payment_status', 'accepted')->where('tanggal_pesan', $time->toDateString())->orderByRaw("FIELD(status , 'ongoing', 'pending', 'finished', 'canceled') ASC")->orderBy('jam_pesan_start')->orderBy('lapangan_olahraga_id')->paginate(10);
    	return view('user.lapangan.show', compact('lapangan', 'orders'));
    }

    // public function show(Lapangan $lapangan, Order $order)
    // {
    // 	Auth::user()->authorizeRoles('CS');
    // 	if(Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
    // 		return abort(401);
    // 	}
    // 	return view('user.lapangan.pemesanan.show', compact('lapangan', 'order'));
    // }
}
