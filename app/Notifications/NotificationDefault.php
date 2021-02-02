<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationDefault extends Notification
{
    use Queueable;


    public $email, $message, $via, $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $email, $message, $via, $subject)
    {
        $this->email = $email;
        $this->message = $message;
        $this->via = $via;
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('mails.customerRegister')
                                // ->to($this->email->to_email)
                                // ->subject('Welcome to Piggy Bank Loyalty');
                                ->subject($this->subject);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'notification' => $this->message
        ];
    }
}
