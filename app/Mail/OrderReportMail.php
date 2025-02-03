<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;

class OrderReportMail extends Mailable
{
    use Queueable, SerializesModels;

    var $fileName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $created_at = Carbon::yesterday();
        // $order_sum='0';
        // $order_sum = Order::whereDate('created_at', $created_at)
        // ->with(['orderItem'=>function($query) {
        //     $query->where('package.course_id','!=',6);
        // }])
        // ->sum('net_amount');
        $order_sum = Order::PaymentStatus()->select('orders.*')->with('student', 'associate.user','third_party.user','orderItems','payment')
        ->whereHas('orderItems.package', function($query) {
            $query->where('course_id','!=',6);
        })
        ->where('payment_mode',1)
        ->whereDate('created_at', $created_at)->sum('net_amount');
        $bcc= '';
        $bcc_ids=[];
        $to_address = Setting::where('key', 'crone_to')->first();
        $bcc_setting = Setting::where('key', 'email_bcc')->first();
        $bcc = $bcc_setting->value;
        $to = $to_address->value;
        $bcc_ids = explode(",",$bcc);
        //info($bcc);
        if($to !='0'){
            $admin_mail = explode(",",$to);
            
            for($i=0;$i<count($admin_mail);$i++){
                $this->to( $admin_mail[$i])
                ->bcc($bcc_ids)
                ->subject('DAILY ORDER REPORT (' . strtoupper(Carbon::yesterday()->toFormattedDateString()) . ')- TOTAL OF '.'₹'.$order_sum)
                ->html('PFA')
                ->attachFromStorage('public/order_reports/' . $this->fileName);
            }
          return true;
        }
    }
}
