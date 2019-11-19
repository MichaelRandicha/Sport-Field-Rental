<?php

namespace App\Console\Commands;

use App\Order;
use Carbon\Carbon;
use App\Notifications\OrderTLE;
use Illuminate\Support\Facades\Notification;
use Illuminate\Console\Command;

class CheckOrderTLE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:tle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check if there's any order that have reached time limit to pay";

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
        $orders = Order::where('payment_status', 'pending')->get();
        foreach($orders as $order){
            $time->setTimezone($order->olahraga->lapangan->timezone);
            if($time->hour >= $order->created_at->hour + Order::$timeLimit && $time->minute >= $order->created_at->minute){
                $order->payment_status = 'canceled';
                $order->status = 'canceled';
                $order->user->notify(new OrderTLE($order));
                $order->save();
            }
        }
    }
}
