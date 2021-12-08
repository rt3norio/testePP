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
    public function __construct(string $payee, string $amount)
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
        Log::info("starting notification job");
        $this->payee; //para evitar erro de variavel não utilizada
        $this->amount; //para evitar erro de variavel não utilizadas
        Http::get("http://o4d9z.mocklab.io/notify");
        // Http::get("http://o4d9z.mocklab.io/notify/$this->payee/$$this->amount"); a API não aceita passar dados extras, mas eu tentei
        Log::info("finishing notification job");
    }
}
