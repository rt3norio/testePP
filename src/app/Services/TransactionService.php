<?php

namespace App\Services;

use App\Interfaces\TransactionServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Jobs\NotificationJob;
use Exception;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransactionService implements TransactionServiceInterface
{
    private $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function pay($payer, $payee, $amount): bool
    {
        // dd($payer);
        if ($payer->balance < $amount) throw new HttpException(403, "not enought funds");
        if ($payer->store) throw new HttpException(403, "store not authorized to transfer funds");

        $authorized = $this->externalAuthorization($payer, $payee, $amount);

        if (!$authorized) throw new HttpException(403, "unauthorized");;

        $this->transactionRepository->transferFunds($payer, $payee, $amount);
        dispatch(new NotificationJob($payee, $amount));
        return true;
    }


    private function externalAuthorization($payer, $payee, $amount)
    {
        return Http::get("https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6/$payer->id/$payee->id/$amount")['message'] == "Autorizado";
    }
}
