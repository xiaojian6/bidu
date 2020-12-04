<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Channels\Sms;

class UserLoginCode extends Notification
{
    use Queueable;

    protected $phone;
    protected $content;
    protected $params;
    protected $country_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($phone, $content, $params, $country_code)
    {
        $this->phone = $phone;
        $this->content = $content;
        $this->params = $params;
        $this->country_code = $country_code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [Sms::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            //
        ];
    }

    public function toSms($notifiable)
    {
        return [
            'phone' => $this->phone,
            'content' => $this->content,
            'params' => $this->params,
            'country_code' => $this->country_code,
        ];
    }
}
