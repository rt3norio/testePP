<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;



class TransactionRepository implements TransactionRepositoryInterface
{
    public function transferFunds($payer, $payee, $amount): bool
    {
        DB::beginTransaction();
        $select = DB::select("select balance, store from users where id = ?", [$payer->id])[0];

        /**
         * essa validação está sendo feita duas vezes, se a conta tem saldo, e se ela não é uma loja.
         * ela foi feita no service emitindo erros amigaveis, mas devido à chamada de API que acontece entre o momento da validação do saldo
         * e a efetiva transação no banco, a validação foi feita novamente dentro de uma transaction, para evitar inconsistências.
         */
        if ($select->balance < $amount || $select->store == 1) {
            DB::rollBack();
            return false;
        }

        DB::insert("
        insert into transactions (payer_user_id, payee_user_id, amount, created_at, updated_at) values (?,?,?, now(), now())
        ", [$payer->id, $payee->id, $amount]);

        DB::update('update users set balance = ? where id = ?', [$payer->balance - $amount, $payer->id]);
        DB::update('update users set balance = ? where id = ?', [$payee->balance + $amount, $payee->id]);

        DB::commit();
        return true;
    }
}
