<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Package;
use Illuminate\Support\Carbon;

class UpdateSellingAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:selling_amount';

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
        $package=Package::all();
        foreach($package as $row){
           
                $pkg = Package::findOrFail($row->id);
                $pkg->selling_amount = $row->selling_price;
                $pkg->update();

            
        }
    }
}
