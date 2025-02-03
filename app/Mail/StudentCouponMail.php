<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentCouponMail extends  Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    var $attributes;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $this->attributes['logo'] = env('WEB_URL') . '/assets/images/logo.png';
        $this->attributes['logo']=env('APP_ENV')=='production'?env('WEB_URL') . '/assets/images/logo.png':public_path('logo.png');
        $this->attributes['web'] = env('WEB_URL');

        if(env('APP_ENV')=='production') {
            $recipients_mail = $this->attributes['recipients_mail'];
            $recipients_mail_count = count($recipients_mail);
            if ($recipients_mail_count > 1) {
                return $this->to($this->attributes['to_email'])
                    ->bcc($this->attributes['recipients_mail'])
                    ->subject('JKSHAH ONLINE - COUPON')
                    ->view('emails.coupon')
                    ->with('attributes', $this->attributes);
            } else {
                return $this->to($this->attributes['to_email'])
                    ->subject('JKSHAH ONLINE - COUPON')
                    ->view('emails.coupon')
                    ->with('attributes', $this->attributes);
            }
        }
        else{
            $recipients_mail = $this->attributes['recipients_mail'];
            $recipients_mail_count = count($recipients_mail);
            if ($recipients_mail_count > 1) {
                return $this->to($this->attributes['to_email'])
                    ->subject('JKSHAH ONLINE - COUPON')
                    ->view('emails.coupon')
                    ->with('attributes', $this->attributes);
            } else {
                return $this->to($this->attributes['to_email'])
                    ->subject('JKSHAH ONLINE - COUPON')
                    ->view('emails.coupon')
                    ->with('attributes', $this->attributes);
            }
        }


    }
}
