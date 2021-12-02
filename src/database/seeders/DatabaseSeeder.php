<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'BANK',
            'email' => 'BANK@BANK.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'taxCode' => '0000',
            'store' => false,
            'balance' => 99999
        ]);

        \App\Models\User::create([
            'name' => 'user1',
            'email' => 'user1@user1.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'taxCode' => '1111',
            'store' => true,
            'balance' => 0
        ]);
        \App\Models\User::create([
            'name' => 'user2',
            'email' => 'user2@user2.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'taxCode' => '2222',
            'store' => false,
            'balance' => 0
        ]);

        // $this->call('UsersTableSeeder');
    }
}
