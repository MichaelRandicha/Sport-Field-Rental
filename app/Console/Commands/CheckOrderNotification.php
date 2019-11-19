<?php

namespace App\Console\Commands;

use App\Order;
use Carbon\Carbon;
use App\Notifications\OrderAlmostStartCS;
use App\Notifications\OrderAlmostStartPO;
use App\Notifications\OrderAlmostEndCS;
use App\Notifications\OrderAlmostEndPO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Console\Command;

class CheckOrderNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Notify User if there's any order that is about to start or finished";

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
            // Log::info('Time : '.$time->format('H:i:s'));
            // Log::info('Order Time : '.$order->jam_pesan_start.' - '.$order->jam_pesan_end);
            if($order->status == 'pending' && $order->jam_pesan_start - $time->hour == 1){
                // Log::info('About to Start Notification Logged');
                Notification::send($order->olahraga->lapangan->cs, new OrderAlmostStartCS($order));
                if($order->user->isPO()){
                    $order->user->notify(new OrderAlmostStartPO($order));
                }
            }elseif($order->status == 'ongoing' && $order->jam_pesan_end - $time->hour == 1){
                if($order->jam_pesan_end - $order->jam_pesan_start > 1){
                    // Log::info('About to End Notification Logged');
                    Notification::send($order->olahraga->lapangan->cs, new OrderAlmostEndCS($order));
                    if($order->user->isPO()){
                        $order->user->notify(new OrderAlmostEndPO($order));
                    }
                }elseif($time->minute == 30){
                    // Log::info('About to End Notification Logged');
                    Notification::send($order->olahraga->lapangan->cs, new OrderAlmostEndCS($order));
                    if($order->user->isPO()){
                        $order->user->notify(new OrderAlmostEndPO($order));
                    }
                }
            }
        }
    }
}
