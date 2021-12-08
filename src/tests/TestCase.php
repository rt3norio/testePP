<?php

use App\Models\Transaction;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    use DatabaseMigrations;
    use DatabaseTransactions;


    protected $user1;
    protected $user2;

    protected function refreshTestData()
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
    protected function deleteAllData()
    {
        Transaction::getQuery()->delete();
        User::getQuery()->delete();
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}
