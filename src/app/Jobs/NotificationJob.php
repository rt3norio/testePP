<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationJob extends Job
{
    private $payee;
    private $amount;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payee, $amount)
    {
        $this->payee = $payee;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Log::info("starting notification job");
        Http::get("http://o4d9z.mocklab.io/notify/$this->payee/$$this->amount");
        Log::info("finishing notification job");
    }
}
