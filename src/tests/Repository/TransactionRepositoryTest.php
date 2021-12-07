<?php

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use App\Services\LogOnlyAuthorizatorService;
use App\Services\WebMockyAuthorizatorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\assertTrue;

class TransactionRepositoryTest extends TestCase
{
    private $user1;
    private $user2;

    private function refreshTestData()
    {
        Transaction::getQuery()->delete();
        User::getQuery()->delete();

        $this->user1 = User::create([
            'name' => 'user1',
            'email' => 'user1@user1.com',
            'password' => '123', // password
            'taxCode' => '0000',
            'store' => false,
            'balance' => 0
        ]);

        $this->user2 =  User::create([
            'name' => 'user2',
            'email' => 'user2@user2.com',
            'password' => '123', // password
            'taxCode' => '0001',
            'store' => false,
            'balance' => 0
        ]);
    }

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
