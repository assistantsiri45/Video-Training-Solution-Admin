<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TechSupportAdminRemarkMail extends Mailable
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
        $to = $this->attributes['admin_mail']; 
        if($to !='0'){
            $admin_mail = explode(",",$to);
        }
        $bcc_ids = [];
        if(!empty($bcc)){
            $bcc_ids = explode(',',$bcc);
        }
        $subject = "JKSHAH ONLINE - Tech Support";
        for($i=0;$i<count($admin_mail);$i++){
            if(count($bcc_ids) !='0'){
                $this->to($admin_mail[$i])
                ->bcc($bcc_ids)
                ->subject($subject)
                ->view('emails.techsupport_remark_admin')
                ->with('attributes', $this->attributes);
            }else{
                $this->to($admin_mail[$i])
                ->subject($subject)
                ->view('emails.techsupport_remark_admin')
                ->with('attributes', $this->attributes);
            }
            //return true;
        }
        return true;
    }
}
