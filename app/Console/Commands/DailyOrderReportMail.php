<?php

namespace App\Console\Commands;

use App\Exports\MailOrderExport;
use App\Mail\OrderReportMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DailyOrderReportMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:daily-order-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        try {
            $fileName = 'ORDER_REPORT_' . time() . '.xlsx';
            Excel::store(new MailOrderExport(), 'public/order_reports/' . $fileName);
            Mail::send(new OrderReportMail($fileName));
            Storage::delete('public/order_reports/' . $fileName);
        } catch (\Exception $exception){
           info($exception->getTraceAsString());
        }
    }
}
