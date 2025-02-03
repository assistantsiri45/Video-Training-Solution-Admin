<?php

namespace App\Console\Commands;

use App\Exports\CallRequestExport;
use App\Mail\DailyCallRequests;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class MailDailyCallRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:daily-call-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for mail daily call requests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileName = 'CALL_REQUESTS_' . time() . '.csv';
        $createdAt = Carbon::yesterday();

        try {
            Excel::store(new CallRequestExport('', '', $createdAt), 'public/call_requests/' . $fileName);
            Mail::send(new DailyCallRequests($fileName));
            Storage::delete('public/call_requests/' . $fileName);
        } catch (\Exception $exception) {
            info($exception->getTraceAsString());
        }
    }
}
