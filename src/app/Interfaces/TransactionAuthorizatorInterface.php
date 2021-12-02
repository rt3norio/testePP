<?php

namespace App\Interfaces;

interface TransactionAuthorizatorInterface
{
    public function authorizeTransaction($payer, $payee, $amount): bool;
}
