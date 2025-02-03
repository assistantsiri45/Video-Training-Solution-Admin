<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseMailRegenerate extends Mailable
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
        $this->attributes['image_url'] = env('ADMIN_URL'). '/storage/packages/';
        $bcc = $this->attributes['email_bcc_user'];

        if(env('APP_ENV')=='production'){
            if(count($bcc) !=0){

                return $this->to($this->attributes['email'])
                ->bcc($bcc)
                ->subject('Congrats! Here’s the confirmation about your course purchase')
                ->attachData($this->attributes['pdf']->output(), "invoice.pdf")
              //  ->bcc(['vishal@jkshahclasses.com','rahuldanait@jkshahclasses.com','helpdesk@jkshahclasses.com'])
               // ->bcc(['vishal@jkshahclasses.com','rahuldanait@jkshahclasses.com'])
                ->view('emails.purchase_success_email_regenerate')
                ->with('attributes', $this->attributes);
            }else{
            return $this->to($this->attributes['email'])
                ->subject('Congrats! Here’s the confirmation about your course purchase')
                ->attachData($this->attributes['pdf']->output(), "invoice.pdf")
              //  ->bcc(['vishal@jkshahclasses.com','rahuldanait@jkshahclasses.com','helpdesk@jkshahclasses.com'])
               // ->bcc(['vishal@jkshahclasses.com','rahuldanait@jkshahclasses.com'])
                ->view('emails.purchase_success_email_regenerate')
                ->with('attributes', $this->attributes);
            }
        }
        else{
            if(count($bcc) !=0){
                return $this->to($this->attributes['email'])
                ->bcc($bcc)
                ->subject('Congrats! Here’s the confirmation about your course purchase')
                ->attachData($this->attributes['pdf']->output(), "invoice.pdf")
                ->view('emails.purchase_success_email_regenerate')
                ->with('attributes', $this->attributes);
            }else{ 
            return $this->to($this->attributes['email'])
                ->subject('Congrats! Here’s the confirmation about your course purchase')
                ->attachData($this->attributes['pdf']->output(), "invoice.pdf")
                ->view('emails.purchase_success_email_regenerate')
                ->with('attributes', $this->attributes);
            }
        }

    }
}
