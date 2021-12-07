<?php

namespace App\Http\Controllers;

use App\Interfaces\TransactionServiceInterface;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TransactionController extends BaseController
{

    private $transactionService;

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }



    public function transact(Request $request)
    {
        $payer = User::findOrFail($request->payer);
        $payee = User::findOrFail($request->payee);

        if ($this->transactionService->pay($payer, $payee, $request->value)) {
            return ['success' => true];
        }

        //a implementação atual do metodo pay() apenas retorna true, ou exception. outra implementação pode retornar false.
        return ['success' => false];
    }

    public function listFromUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $transactions = Transaction::fromUser($user)->orderBy('created_at')->get();
        return $transactions;
    }
}
