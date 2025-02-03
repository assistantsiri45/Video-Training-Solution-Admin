<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var array $attributes */
    var $attributes = [];

    /**
     * Create a new notification instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
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

    /**
     * Get the text message representation of the notification
     *
     * @param  mixed  $notifiable
     * @return mixed
     */
    public function toSms($notifiable)
    {
        // return "Dear {$this->attributes['name']},\nYou now have access to {$this->attributes['package_name']}. Log into https://online.jkshahclasses.com and check your dashboard for lectures, study materials and tests related to the course. In case of any technical issues, please WhatsApp on 7304454714 or write to us at helpdesk@jkshahclasses.com.\nWishing you success - JKSHAH Classes.";
        return "Student, {$this->attributes['name']},\nYou now have access to {$this->attributes['package_name']}. Log into https://online.jkshahclasses.com and check your dashboard for lectures, study materials and tests related to the course. In case of any technical issues, please WhatsApp on 7304454714 or write to us at helpdesk@jkshahclasses.com.\nWishing you success - JKSHAH Classes.";
    }
}
