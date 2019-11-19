<?php

namespace App\Notifications;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderAlmostStartCS extends Notification
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
        $url = route('pemesanan.index', ['lapangan' => $this->order->olahraga->lapangan]);
        return (new MailMessage)
                    ->greeting('Pemesanan akan segera dimulai!')
                    ->line('Pemesanan di Lapangan '.$this->order->olahraga->lapangan->name.' - '.$this->order->olahraga->name.' akan segera dimulai, tekan tombol berikut untuk melihat daftar pemesanan pada hari ini')
                    ->action('Daftar Pemesanan', $url)
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
