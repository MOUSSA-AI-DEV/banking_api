<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\AccountService;
use App\Services\TransactionService;
use App\Services\TransferService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(AccountService $accountService, TransactionService $transactionService, TransferService $transferService): void
    {
        // 1. Create Users (Family)
        $papa = User::create(['name' => 'Papa', 'email' => 'papa@banking.com', 'password' => Hash::make('password123')]);
        $maman = User::create(['name' => 'Maman', 'email' => 'maman@banking.com', 'password' => Hash::make('password123')]);
        $fils = User::create(['name' => 'Fils', 'email' => 'fils@banking.com', 'password' => Hash::make('password123')]);

        // 2. Create Accounts using AccountService
        // Papa's Checking Account (which he will share with Maman)
        $checkingAccount = $accountService->createAccount($papa, [
            'type' => 'checking',
            'initial_balance' => 0,
            'overdraft_limit' => 500,
        ]);

        // Maman's Savings Account
        $savingsAccount = $accountService->createAccount($maman, [
            'type' => 'savings',
            'initial_balance' => 0,
            'interest_rate' => 2.5,
        ]);

        // Fils' Minor Account (Created by Papa, owned by Fils)
        $minorAccount = $accountService->createAccount($papa, [
            'type' => 'minor',
            'minor_id' => $fils->id,
            'initial_balance' => 0,
            'withdraw_limit' => 50,
        ]);

        // 3. Add Co-Holders
        // Papa adds Maman as a co-holder to his checking account
        $accountService->addCoHolder($checkingAccount, $maman, 'co_holder');

        // 4. Initial Transactions
        $transactionService->deposit($checkingAccount, 5000); // Salary for checking
        $transactionService->deposit($savingsAccount, 2000);  // Savings deposit
        $transactionService->deposit($minorAccount, 100);     // Pocket money

        // 5. Conduct a Transfer
        // Papa sends money from checking to Maman's savings
        $transferService->transfer($checkingAccount, $savingsAccount, 300);

        // Print details to console for the tester
        $this->command->info('Database Seeded Successfully!');
        $this->command->info('-------------------------------');
        $this->command->info('Papa Account  : papa@banking.com / password123');
        $this->command->info('Maman Account : maman@banking.com / password123');
        $this->command->info('Fils Account  : fils@banking.com / password123');
        $this->command->info('Checking Bal  : ' . $checkingAccount->fresh()->balance . ' (Shared Papa & Maman)');
        $this->command->info('Savings Bal   : ' . $savingsAccount->fresh()->balance . ' (Maman)');
        $this->command->info('Minor Bal     : ' . $minorAccount->fresh()->balance . ' (Fils & Papa Guardian)');
    }
}
