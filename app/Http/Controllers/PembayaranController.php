<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Image;
use App\Order;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrder;
use App\Notifications\OrderTLE;
use App\Notifications\OrderCanceled;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{

	protected $identityImagePath;
	protected $paymentImagePath;

    public function __construct(){
        $this->identityImagePath = storage_path('app/public/images/user/pembayaran/identity_card_img/');
        $this->paymentImagePath = storage_path('app/public/images/user/pembayaran/payment_img/');
    }

    public function index(){
    	Auth::user()->authorizeRoles('PO');
    	$orders = Auth::user()->order()->orderByRaw("FIELD(payment_status, 'pending', 'Pending', 'accepted', 'denied', 'canceled') ASC")->orderByRaw("FIELD(status, 'ongoing', 'pending', 'finished', 'canceled') ASC")->paginate(5);
    	$now = Carbon::now();
    	foreach ($orders as $order) {
            if($order->payment_status == 'pending'){
        		$time = $order->created_at;
        		$time->hour += Order::$timeLimit;
        		if($now > $time){
        			$order->payment_status = 'canceled';
                    $order->status = 'canceled';                    
                    $order->save();
                    $order->user->notify(new OrderTLE($order));
                }
            }
    	}
    	return view('user.pembayaran.index', compact('orders'));
    }

    public function cancel(Order $order){
    	Auth::user()->authorizeRoles('PO');
    	if($order->user->id != Auth::user()->id){
    		return abort(401);
    	}
    	$order->payment_status = 'canceled';
        $order->status = 'canceled';
    	$order->save();
        $order->user->notify(new OrderCanceled($order));
    	return redirect()->back();
    }

    public function edit(Order $order){
    	Auth::user()->authorizeRoles('PO');
    	if($order->user->id != Auth::user()->id || $order->payment_status != "pending"){
    		return abort(401);
    	}
    	$time = $order->created_at;
    	$time->hour += Order::$timeLimit;
    	if(Carbon::now() > $time){
    		$order->payment_status = 'canceled';
            $order->status = 'canceled';
            $order->save();
            $order->user->notify(new OrderTLE($order));
    		return redirect()->route('PO.pembayaran.index')->with('TLE', 'Waktu pembayaran telah melebihi batas waktu yang telah diberikan. Payment Order Dibatalkan');
    	}
    	return view('user.pembayaran.edit', compact('order'));
    }

    public function update(Order $order, Request $request){
    	Auth::user()->authorizeRoles('PO');
		if($order->user->id != Auth::user()->id || $order->payment_status != "pending"){
    		return abort(401);
    	}
    	$time = $order->created_at;
    	$time->hour += Order::$timeLimit;
    	if(Carbon::now() > $time){
    		$order->payment_status = 'canceled';
            $order->status = 'canceled';
    		$order->save();
            $order->user->notify(new OrderTLE($order));
    		return redirect()->route('PO.pembayaran.index')->with('TLE', 'Waktu pembayaran telah melebihi batas waktu yang telah diberikan. Payment Order Dibatalkan');
    	}
    	$required = $order->harga_per_jam == 0 ? 'sometimes' : 'required';
    	$request->validate([
    		'identity_card_image' => ['required', 'image'],
    		'payment_image' => [$required, 'image'],
    	]);
    	if($request->has('identity_card_image')){
            if (!File::isDirectory($this->identityImagePath)) {
                File::makeDirectory($this->identityImagePath, 0755, true);
            }
            //Saving Original
            $file = $request->file('identity_card_image');
            $image = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->save($this->identityImagePath . '/' . $image);

            $order->identity_card_img = $image;
            $order->save();
        }
        if($request->has('payment_image')){
            if (!File::isDirectory($this->paymentImagePath)) {
                File::makeDirectory($this->paymentImagePath, 0755, true);
            }
            //Saving Original
            $file = $request->file('payment_image');
            $image = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->save($this->paymentImagePath . '/' . $image);

            $order->payment_img = $image;
            $order->save();
        }
        $order->payment_status = "Pending";
        $order->save();
    	
        Notification::send($order->olahraga->lapangan->cs, new NewOrder($order));
    	return redirect()->route('PO.pembayaran.index');
    }

    public function show(Order $order){
    	Auth::user()->authorizeRoles('PO');
    	if($order->user->id != Auth::user()->id){
    		return abort(401);
    	}
    	return view('user.pembayaran.show', compact('order'));
    }
}
