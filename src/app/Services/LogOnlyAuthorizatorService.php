<?php

namespace App\Services;

use App\Interfaces\TransactionAuthorizatorInterface;
use Illuminate\Support\Facades\Log;

class LogOnlyAuthorizatorService implements TransactionAuthorizatorInterface
{
    public function authorizeTransaction($payer, $payee, $amount): bool
    {
        Log::info("Authorizator Called with parameters", [$payer, $payee, $amount]);
        return true;
    }
}
