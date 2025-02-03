<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
       
        $this->data = $data;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
     
           $response = Http::get('https://k3digitalmedia.co.in/websms/api/http/index.php',[
            'username' => 'K3JKSHAH',
            'apikey' => '67311-C0DBD',
            'apirequest' => 'Template',
            'sender' => 'JKSHAH',
            'mobile' => $this->data['phone'],
            'TemplateID' =>  $this->data['template_id'],
            'Values' =>  $this->data['name'],@$this->data['pname'],@$this->data['expiry'], 
            'route' => 'ServiceImplicit'
        ]);
       
    }
}
