<?php

namespace App\Http\Controllers;

use App\Interfaces\TransactionServiceInterface;
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
        return ['success' => false];
    }

    public function create(Request $request)
    {
        return User::firstOrCreate([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'taxCode' => $request->taxCode,
            'store' => $request->moneySink
        ]);
    }
}
