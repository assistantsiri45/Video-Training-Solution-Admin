<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyCallRequests extends Mailable
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
        return $this->to(env('CALL_REQUESTS_MAIL'))
            ->from(env('MAIL_FROM_ADDRESS'), 'ADMIN')
            ->subject('DAILY CALL REQUESTS (' . strtoupper(Carbon::yesterday()->toFormattedDateString()) . ')')
            ->html('PFA')
            ->attachFromStorage('public/call_requests/' . $this->fileName);
    }
}
