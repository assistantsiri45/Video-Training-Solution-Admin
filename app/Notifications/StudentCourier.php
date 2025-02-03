<?php

namespace App\Notifications;
use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class StudentCourier extends Notification implements ShouldQueue
{
    use Queueable;
    var $attributes;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($attributes)
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
        // Your {{$attributes->orderItem->package->name}} has been despatched using {{$attributes->courier->name}} on {{ date('d-m-Y',strtotime($attributes->created_at))}}.
        // The tracking number for the package is {{$attributes->dispatch_detail}}. Please use the {{$attributes->courier->url}} to track your package.
        return "Student,Your {$this->attributes->orderItem->package->name} despatched using {$this->attributes->courier->name} tracking no is {$this->attributes->dispatch_detail}. Track using {$this->attributes->courier->url} JKSHAH Education";
    }
}
