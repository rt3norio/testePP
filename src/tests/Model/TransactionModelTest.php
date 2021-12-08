<?php

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class TransactionModelTest extends TestCase
{

    public function testTransactionRelationshipWithUser()
    {
        $this->refreshTestData();
        $this->user1->balance = 200;
        $this->user1->save();

        $payerId = $this->user1->id;
        $payeeId = $this->user2->id;

        $this->post('/transaction', [
            "value" => 50.00,
            "payer" => $payerId,
            "payee" => $payeeId
        ]);

        $transaction = Transaction::first();
        $this->assertEquals($payerId, $transaction->payer->id);
        $this->assertEquals($payeeId, $transaction->payee->id);
    }
}
