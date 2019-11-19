<?php

namespace App\Notifications;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStartPO extends Notification
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
        $url = route('PO.pembayaran.show', ['order' => $this->order]);
        return (new MailMessage)
                    ->greeting('Pemesanan anda telah dimulai!')
                    ->line('Pemesanan anda di Lapangan '.$this->order->olahraga->lapangan->name.' - '.$this->order->olahraga->name.' telah dimulai, tekan tombol berikut untuk melihat pembayaran dari pemesanan yang dimaksud')
                    ->action('Lihat Pembayaran', $url)
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
