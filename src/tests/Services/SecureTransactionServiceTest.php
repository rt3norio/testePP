<?php

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use App\Services\LogOnlyAuthorizatorService;
use App\Services\SecureTransactionService;
use App\Services\WebMockyAuthorizatorService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SecureTransactionServiceTest extends TestCase
{
    public function testIfRepositoryPayMethodTransfersMoneyToStore()
    {
        $this->refreshTestData();
        $this->user1->balance = 1000;
        $this->user1->save();

        $this->user2->store = true;
        $this->user2->save();

        $this->expectsJobs('App\Jobs\NotificationJob');
        $transactionRepository = new TransactionRepository();
        $transactionAuthorizator = new LogOnlyAuthorizatorService();
        $secureTransactionService = new SecureTransactionService($transactionRepository, $transactionAuthorizator);
        $this->assertTrue($secureTransactionService->pay($this->user1, $this->user2, 500));

        $this->user1->refresh();
        $this->user2->refresh();

        $this->assertEquals(500, $this->user1['balance']);
        $this->assertEquals(500, $this->user2['balance']);
    }

    public function testIfRepositoryPayMethodRefusesTransferingFromStore()
    {
        $this->refreshTestData();

        $this->user1->balance = 1000;
        $this->user1->store = true;
        $this->user1->save();

        $this->expectExceptionMessage('store not authorized to transfer funds');

        $transactionRepository = new TransactionRepository();
        $transactionAuthorizator = new LogOnlyAuthorizatorService();
        $secureTransactionService = new SecureTransactionService($transactionRepository, $transactionAuthorizator);
        $this->assertFalse($secureTransactionService->pay($this->user1, $this->user2, 500));

        $this->user1->refresh();
        $this->user2->refresh();

        $this->assertEquals(1000, $this->user1['balance']);
        $this->assertEquals(0, $this->user2['balance']);
    }

    public function testIfRepositoryPayMethodRefusesWhenWithoutFunds()
    {
        $this->refreshTestData();

        $this->user1->balance = 1000;
        $this->user1->store = true;
        $this->user1->save();


        $this->expectExceptionMessage('not enought funds');

        $transactionRepository = new TransactionRepository();
        $transactionAuthorizator = new LogOnlyAuthorizatorService();
        $secureTransactionService = new SecureTransactionService($transactionRepository, $transactionAuthorizator);
        $this->assertFalse($secureTransactionService->pay($this->user1, $this->user2, 1500));


        $this->user1->refresh();
        $this->user2->refresh();

        $this->assertEquals(1000, $this->user1['balance']);
        $this->assertEquals(0, $this->user2['balance']);
    }


    public function testIfRepositoryPayMethodReturnsCorrectlyWhenExternalAuthorizatorFails()
    {
        $this->refreshTestData();
        $this->user1->balance = 1000;
        $this->user1->save();

        $this->user2->store = true;
        $this->user2->save();

        $this->expectExceptionMessage('failed to authorize transaction');

        Http::shouldReceive('get')
            ->once()
            ->andThrow(new Exception('falha ao enviar dados'));
        Log::shouldReceive('info')
            ->once()
            ->andReturn([]);


        $transactionRepository = new TransactionRepository();
        $transactionAuthorizator = new WebMockyAuthorizatorService();
        $secureTransactionService = new SecureTransactionService($transactionRepository, $transactionAuthorizator);
        $this->assertTrue($secureTransactionService->pay($this->user1, $this->user2, 500));

        $this->user1->refresh();
        $this->user2->refresh();

        $this->assertEquals(500, $this->user1['balance']);
        $this->assertEquals(500, $this->user2['balance']);
    }
}
