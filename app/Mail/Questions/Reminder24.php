<?php

namespace App\Mail\Questions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reminder24 extends Mailable
{
    use Queueable, SerializesModels;

    var $attributes;

    /**
     * Create a new message instance.
     *
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
    public function build(): Reminder24
    {
        if (env('QUESTION_REMINDER_MAIL')) {
            return $this->to($this->attributes['professor_email'])
                ->cc(env('QUESTION_REMINDER_MAIL'))
                ->subject('PENDING FOR 24 Hrs - JKSHAH ONLINE')
                ->view('emails.questions.reminder24')
                ->with('attributes', $this->attributes);
        }

        return $this->to($this->attributes['professor_email'])
            ->subject('PENDING FOR 24 Hrs - JKSHAH ONLINE')
            ->view('emails.questions.reminder24')
            ->with('attributes', $this->attributes);
    }
}
