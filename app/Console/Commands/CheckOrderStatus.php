<?php

namespace App\Console\Commands;

use App\Order;
use Carbon\Carbon;
use App\Notifications\OrderStartCS;
use App\Notifications\OrderStartPO;
use App\Notifications\OrderEndCS;
use App\Notifications\OrderEndPO;
use Illuminate\Support\Facades\Notification;
use Illuminate\Console\Command;

class CheckOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check if there's any order that is now started or finished";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = Carbon::now();
        $orders = Order::where('payment_status', 'accepted')->where('status', '!=', 'finished')->where('tanggal_pesan', $time->toDateString())->get();
        foreach($orders as $order){
            $time->setTimezone($order->olahraga->lapangan->timezone);
            if($order->status == 'pending' && $time->hour >= $order->jam_pesan_start){
                $order->status = 'ongoing';
                $order->save();
                $order->user->notify(new OrderStartPO($order));
                Notification::send($order->olahraga->lapangan->cs, new OrderStartCS($order));
            }elseif($order->status == 'ongoing'){
                if($time->hour >= $order->jam_pesan_end){
                    $order->status = 'finished';
                    $order->save();
                    $order->user->notify(new OrderEndPO($order));
                    Notification::send($order->olahraga->lapangan->cs, new OrderEndCS($order));
                }
            }
        }
        $orders = Order::where('payment_status', 'accepted')->where('status', '!=', 'finished')->where('tanggal_pesan', Carbon::yesterday())->where('jam_pesan_end', 24)->get();
        foreach($orders as $order){
            $time->setTimezone($order->olahraga->lapangan->timezone);
            if($time->toDateString() >= $order->tanggal_pesan){
                $order->status = 'finished';
                $order->save();
                $order->user->notify(new OrderEndPO($order));
                Notification::send($order->olahraga->lapangan->cs, new OrderEndCS($order));
            }
        }
    }
}
