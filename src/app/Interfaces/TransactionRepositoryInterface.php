<?php

namespace App\Interfaces;

interface TransactionRepositoryInterface
{
    public function transferFunds($payer, $payee, $amount): bool;
}
