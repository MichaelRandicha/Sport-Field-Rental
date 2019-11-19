<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\Lapangan;
use App\LapanganOlahraga;
use App\Notifications\OrderAccepted;
use App\Notifications\OrderDenied;
use Illuminate\Http\Request;

class DaftarPembayaranController extends Controller
{
    public function index(Lapangan $lapangan, LapanganOlahraga $olahraga){
    	Auth::user()->authorizeRoles('CS');
    	if($olahraga->lapangan->id != $lapangan->id || Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
    		return abort(401);
    	}
    	$cs = $lapangan->cs->pluck('id')->toArray();
    	if(!in_array($lapangan->user->id, $cs)){
    		array_push($cs, $lapangan->user->id);
    	}

    	$orders = $olahraga->order()->whereNotIn('user_id', $cs)->orderByRaw("FIELD(payment_status , 'Pending', 'pending', 'accepted', 'denied', 'canceled') ASC")->orderBy('tanggal_pesan', 'DESC')->orderBy('jam_pesan_start')->paginate(5);
    	return view('user.lapangan.olahraga.show', compact('lapangan', 'olahraga', 'orders'));
    }

    public function show(Lapangan $lapangan, LapanganOlahraga $olahraga, Order $order){
    	Auth::user()->authorizeRoles('CS');
    	if($olahraga->lapangan->id != $lapangan->id || $order->olahraga->id != $olahraga->id || Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
    		return abort(401);
    	}
    	return view('user.lapangan.olahraga.pembayaran.show', compact('lapangan', 'olahraga', 'order'));
    }

    public function accept(Lapangan $lapangan, LapanganOlahraga $olahraga, Order $order){
        Auth::user()->authorizeRoles('CS');
    	if($olahraga->lapangan->id != $lapangan->id || $order->olahraga->id != $olahraga->id || Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        $order->payment_status = "accepted";
        $order->save();
        $order->user->notify(new OrderAccepted($order));
        return redirect()->route('pembayaran.index', compact('lapangan', 'olahraga'));
    }


    public function deny(Lapangan $lapangan, LapanganOlahraga $olahraga, Order $order){
        Auth::user()->authorizeRoles('CS');
        if($olahraga->lapangan->id != $lapangan->id || $order->olahraga->id != $olahraga->id || Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        $order->payment_status = "denied";
        $order->status = 'canceled';
        $order->save();
        $order->user->notify(new OrderDenied($order));
        return redirect()->route('pembayaran.index', compact('lapangan', 'olahraga'));
    }

}
