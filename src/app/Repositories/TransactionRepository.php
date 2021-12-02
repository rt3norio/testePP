<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function transferFunds($payer, $payee, $amount): void
    {
        DB::beginTransaction();

        DB::insert("
            insert into transactions (amount, payer_user_id, payee_user_id) values (?,?,?)
        ", [$amount, $payer->id, $payee->id]);

        DB::update('update users set balance = ? where id = ?', [$payer->balance - $amount, $payer->id]);
        DB::update('update users set balance = ? where id = ?', [$payee->balance + $amount, $payee->id]);

        DB::commit();
    }
}
