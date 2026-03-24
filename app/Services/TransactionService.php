<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionService
{
    
    public function deposit(Account $account, float $amount, ?string $transferId = null): Transaction
    {
        return DB::transaction(function () use ($account, $amount, $transferId) {
            $transaction = Transaction::create([
                'account_id' => $account->id,
                'amount' => $amount,
                'type' => 'deposit',
                'transfer_id' => $transferId,
            ]);

            $account->increment('balance', $amount);

            return $transaction;
        });
    }
    // Handle simple withdrawal operation.
    public function withdraw(Account $account, float $amount, ?string $transferId = null): Transaction
    {
        return DB::transaction(function () use ($account, $amount, $transferId) {
           
            if ($account->type === 'minor') {
                if ($account->withdraw_limit && $amount > $account->withdraw_limit) {
                    throw new Exception("Withdrawal limit exceeded for minor account.");
                }
            }

            $availableBalance = $account->balance + ($account->overdraft_limit ?? 0);
            if ($amount > $availableBalance) {
                throw new Exception("Insufficient funds.");
            }

            $transaction = Transaction::create([
                'account_id' => $account->id,
                'amount' => $amount,
                'type' => 'withdraw',
                'transfer_id' => $transferId,
            ]);

            $account->decrement('balance', $amount);

            return $transaction;
        });
    }
}
