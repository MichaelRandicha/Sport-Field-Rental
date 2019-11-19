<?php

namespace App\Notifications;

use App\Order;
use Illuminate\Bus\Queueable;
// use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrder extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage[
    //         'order' => $this->order,
    //         'lapangan' => $this->order->olahraga->lapangan,
    //         'olahraga' => $this->order->olahraga,
    //     ];
    // }

    public function toDatabase($notifiable)
    {
        return [
            'order' => $this->order,
            'lapangan' => $this->order->olahraga->lapangan,
            'olahraga' => $this->order->olahraga,
        ];
    }

    public function toMail($notifiable)
    {
        $url = route('pembayaran.index', ['lapangan' => $this->order->olahraga->lapangan, 'olahraga' => $this->order->olahraga]);
        return (new MailMessage)
                    ->greeting('Pemesanan baru di CariLapangan!')
                    ->line('Terdapat pemesanan baru di Lapangan '.$this->order->olahraga->lapangan->name.' - '.$this->order->olahraga->name.', silahkan verifikasi pembayaran pemesanan tersebut')
                    ->action('Verifikasi', $url)
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
