<?php

use Illuminate\Support\Facades\Http;

class TransactionControllerTest extends TestCase
{


    public function testSendMoneyWithNoProblems()
    {
        $this->refreshTestData();
        $this->user1->balance = 200;
        $this->user1->save();

        $payerId = $this->user1->id;
        $payeeId = $this->user2->id;

        Http::shouldReceive('get')
            ->once()
            ->andReturn(['message' => 'Autorizado']);

        $this->post('/transaction', [
            "value" => 100.00,
            "payer" => $payerId,
            "payee" => $payeeId
        ]);
        $this->response->assertOk();

        $response = json_decode($this->response->getContent());
        $this->assertTrue($response->success);
    }

    public function testPayMethodReturningFalse()
    {
        $service = $this->getMockBuilder('App\Services\SecureTransactionService')->disableOriginalConstructor()->getMock();
        $service->expects($this->any())
            ->method('pay')
            ->willReturn(false);
        $this->app->instance('App\Services\SecureTransactionService', $service);


        $this->refreshTestData();

        $payerId = $this->user1->id;
        $payeeId = $this->user2->id;

        // Http::shouldReceive('get')
        //     ->once()
        //     ->andReturn(['message' => 'Autorizado']);

        $this->post('/transaction', [
            "value" => 100.00,
            "payer" => $payerId,
            "payee" => $payeeId
        ]);
        $this->response->assertOk();

        $response = json_decode($this->response->getContent());
        $this->assertFalse($response->success);
    }



    public function testSendMoneyWithfailedAuthorizatorResponse()
    {
        $this->refreshTestData();
        $this->user1->balance = 200;
        $this->user1->save();

        $payerId = $this->user1->id;
        $payeeId = $this->user2->id;

        Http::shouldReceive('get')
            ->once()
            ->andReturn(['message' => 'Não Autorizado']);

        $this->post('/transaction', [
            "value" => 100.00,
            "payer" => $payerId,
            "payee" => $payeeId
        ]);
        $this->response->assertForbidden();
    }

    public function testSendMoneyWithInsuficientFunds()
    {
        $this->refreshTestData();
        $this->user1->balance = 200;
        $this->user1->save();

        $payerId = $this->user1->id;
        $payeeId = $this->user2->id;

        $this->post('/transaction', [
            "value" => 200.01,
            "payer" => $payerId,
            "payee" => $payeeId
        ]);
        $this->response->assertForbidden();
    }

    public function testSendMoneyWhileBeingStore()
    {
        $this->refreshTestData();
        $this->user1->balance = 200;
        $this->user1->store = true;
        $this->user1->save();

        $payerId = $this->user1->id;
        $payeeId = $this->user2->id;

        $this->post('/transaction', [
            "value" => 100.00,
            "payer" => $payerId,
            "payee" => $payeeId
        ]);
        $this->response->assertForbidden();
    }


    public function testListingTransactions()
    {
        $this->refreshTestData();
        $this->user1->balance = 200;
        $this->user1->save();

        $payerId = $this->user1->id;
        $payeeId = $this->user2->id;

        Http::shouldReceive('get')
            ->twice()
            ->andReturn(['message' => 'Autorizado']);

        $this->post('/transaction', [
            "value" => 50.00,
            "payer" => $payerId,
            "payee" => $payeeId
        ]);
        $this->post('/transaction', [
            "value" => 20.00,
            "payer" => $payerId,
            "payee" => $payeeId
        ]);
        $this->get("/user/$payerId/transactions");

        $this->response->assertOk();

        $response = json_decode($this->response->getContent());

        $this->assertEquals("50.00", $response[0]->amount);
        $this->assertEquals($payerId, $response[0]->payer_user_id);

        $this->assertEquals("20.00", $response[1]->amount);
        $this->assertEquals($payerId, $response[1]->payer_user_id);
    }
}
