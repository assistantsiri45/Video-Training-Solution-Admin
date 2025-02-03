<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var array $attributes */
    var $attributes;

    /**
     * Create a new message instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes)
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

        return $this->to($this->attributes['email'])
            ->subject('JKSHAH ONLINE - WELCOME')
            ->view('emails.registration.mail')
            ->with('attributes', $this->attributes);
    }
}
