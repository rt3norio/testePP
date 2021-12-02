<?php

namespace App\Interfaces;

interface TransactionServiceInterface
{
    public function pay($payer, $payee, $amount): bool;
}
