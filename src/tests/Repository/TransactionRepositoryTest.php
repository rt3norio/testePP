<?php

use App\Repositories\TransactionRepository;


use function PHPUnit\Framework\assertTrue;

class TransactionRepositoryTest extends TestCase
{

    public function testIfRepositoryPayMethodTransfersMoneyToStore()
    {
        $this->refreshTestData();
        $this->user1->balance = 1000;
        $this->user1->save();

        $this->user2->store = true;
        $this->user2->save();

        $transactionRepository = new TransactionRepository();
        $this->assertTrue($transactionRepository->transferFunds($this->user1, $this->user2, 500));

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


        $transactionRepository = new TransactionRepository();
        $this->assertFalse($transactionRepository->transferFunds($this->user1, $this->user2, 500));


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


        $transactionRepository = new TransactionRepository();
        $this->assertFalse($transactionRepository->transferFunds($this->user1, $this->user2, 1500));


        $this->user1->refresh();
        $this->user2->refresh();

        $this->assertEquals(1000, $this->user1['balance']);
        $this->assertEquals(0, $this->user2['balance']);
    }
}
