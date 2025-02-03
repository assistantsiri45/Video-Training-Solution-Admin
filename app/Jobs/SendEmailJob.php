<?php

namespace App\Jobs;

use App\Notifications\SendCustomnotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ErrorLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    protected $student;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details, $student)
    {
        $this->details = $details;
        $this->student = $student;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $notification = new SendCustomnotificationMail($this->details);
            $this->student->notify($notification);
        } catch (\Exception $exception) {
            $data1['response']  =$exception->getMessage();
            ErrorLog::insert($data1);
            info($exception->getMessage());
        }
    }
}
