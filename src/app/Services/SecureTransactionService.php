<?php

namespace App\Services;

use App\Interfaces\TransactionAuthorizatorInterface;
use App\Interfaces\TransactionServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Jobs\NotificationJob;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class SecureTransactionService implements TransactionServiceInterface
{
    private $transactionRepository;
    private $transactionAuthorizator;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        TransactionAuthorizatorInterface $transactionAuthorizator
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->transactionAuthorizator = $transactionAuthorizator;
    }

    public function pay($payer, $payee, $amount): bool
    {
        if ($payer->balance < $amount) throw new HttpException(403, "not enought funds");
        if ($payer->store) throw new HttpException(403, "store not authorized to transfer funds");

        try {
            $authorized = $this->transactionAuthorizator->authorizeTransaction($payer, $payee, $amount);
        } catch (Throwable $t) {
            throw new HttpException(403, 'failed to authorize transaction');
        }

        if (!$authorized) throw new HttpException(403, "unauthorized");

        $databaseOperationSuccessfull = $this->transactionRepository->transferFunds($payer, $payee, $amount);

        if (!$databaseOperationSuccessfull) throw new HttpException(403, "database error");

        dispatch(new NotificationJob($payee, $amount));
        return true;
    }
}
