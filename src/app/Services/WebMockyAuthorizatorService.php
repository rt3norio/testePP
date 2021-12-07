<?php

namespace App\Services;

use App\Interfaces\TransactionAuthorizatorInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebMockyAuthorizatorService implements TransactionAuthorizatorInterface
{
    public function authorizeTransaction($payer, $payee, $amount): bool
    {
        Log::info('Authorizing transaction via mocky service', [$payer, $payee, $amount]);
        $response = Http::get("https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6/$payer->id/$payee->id/$amount")['message'];
        return $response == "Autorizado";
    }
}
