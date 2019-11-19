<?php

namespace App\Notifications;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderEndPO extends Notification
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
        $url = route('olahraga.review.index', ['lapangan' => $this->order->olahraga->lapangan, 'olahraga' => $this->order->olahraga]);
        return (new MailMessage)
                    ->greeting('Pemesanan anda telah selesai!')
                    ->line('Pemesanan anda di Lapangan '.$this->order->olahraga->lapangan->name.' - '.$this->order->olahraga->name.' telah selesai, silahkan berikan ulasan terhadap lapangan olahraga tersebut dengan menekan tombol berikut')
                    ->action('Beri Ulasan', $url)
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
