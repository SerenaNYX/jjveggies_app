<?php

namespace App\Notifications;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnquiryResponse extends Notification implements ShouldQueue
{
    use Queueable;

    public $enquiry;

    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Response to Your Enquiry #' . $this->enquiry->id)
            ->line('Your enquiry has been responded to by our staff.')
            ->line('Enquiry: ' . $this->enquiry->message)
            ->line('Response: ' . $this->enquiry->response)
        /*    ->action('View Enquiry', route('enquiries.show', $this->enquiry))*/
            ->line('Thank you for contacting us!');
    }
}