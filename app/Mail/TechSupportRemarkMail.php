<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TechSupportRemarkMail extends Mailable
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
     
        $this->attributes['logo']= public_path('logo.png');
        $this->attributes['web'] = env('WEB_URL');
        $bcc = $this->attributes['email_bcc'];
        $bcc_ids = [];
        if(!empty($bcc)){
            $bcc_ids = explode(',',$bcc);
        }
        $subject = "JKSHAH ONLINE - Tech Support";
        if(count($bcc_ids) !='0'){
            return $this->to($this->attributes['to'])
                ->bcc($bcc_ids)
                ->subject($subject)
                ->view('emails.techsupport_remark')
                ->with('attributes', $this->attributes);
        }else{
            return $this->to($this->attributes['to'])
                ->subject($subject)
                ->view('emails.techsupport_remark')
                ->with('attributes', $this->attributes);
        }

    }
}
