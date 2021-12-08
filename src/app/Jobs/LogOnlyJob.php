<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class LogOnlyJob extends Job
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
        Log::info("starting notification job");
        $this->payee; //para evitar erro de variavel não utilizada
        $this->amount; //para evitar erro de variavel não utilizadas
        Log::info("finishing notification job");
    }
}
