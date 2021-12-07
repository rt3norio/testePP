<?php

namespace App\Services;

use App\Interfaces\TransactionAuthorizatorInterface;
use App\Interfaces\TransactionServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Jobs\NotificationJob;

class InsecureTransactionService implements TransactionServiceInterface
{
    private $transactionRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        TransactionAuthorizatorInterface $transactionAuthorizator
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->transactionAuthorizator = $transactionAuthorizator;
    }

    public function pay($payer, $payee, $amount): bool
    {
        $this->transactionRepository->transferFunds($payer, $payee, $amount);
        dispatch(new NotificationJob($payee, $amount));
        return true;
    }
}
