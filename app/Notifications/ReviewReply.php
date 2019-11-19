<?php

namespace App\Notifications;

use App\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReviewReply extends Notification
{
    use Queueable;

    protected $review;
    
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable){
        return [
            'review' => $this->review,
            'lapangan' => $this->review->olahraga->lapangan,
            'olahraga' => $this->review->olahraga,
        ];
    }

    public function toMail($notifiable)
    {
        $url = route('olahraga.review.index', ['lapangan' => $this->review->olahraga->lapangan, 'olahraga' => $this->review->olahraga]);
        return (new MailMessage)
                    ->greeting('Terdapat Tanggapan pada Ulasan anda!')
                    ->line('Terdapat tanggapan pada ulasan anda di Lapangan '.$this->review->olahraga->lapangan->name.' - '.$this->review->olahraga->name.', silahkan menekan tombol berikut untuk melihat tanggapan tersebut')
                    ->action('Lihat Ulasan', $url)
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
