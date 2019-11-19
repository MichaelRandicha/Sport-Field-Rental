<?php

namespace App\Notifications;

use App\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewReview extends Notification
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
                    ->greeting('Ulasan baru di CariLapangan!')
                    ->line('Terdapat ulasan baru di Lapangan '.$this->review->olahraga->lapangan->name.' - '.$this->review->olahraga->name.', jika anda ingin memberikan tanggapan, anda dapat menekan tombol berikut')
                    ->action('Beri Tanggapan', $url)
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
