<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Str;

class AccountService
{
    
    public function createAccount(User $user, array $data): Account
    {
        return Account::create([
            'user_id' => $user->id,
            'type' => $data['type'], // checking | savings | minor
            'status' => $data['status'] ?? 'active',
            'balance' => $data['initial_balance'] ?? 0,
            'overdraft_limit' => $data['overdraft_limit'] ?? null,
            'interest_rate' => $data['interest_rate'] ?? null,
            'withdraw_limit' => $data['withdraw_limit'] ?? null,
            'guardian_id' => $data['guardian_id'] ?? null,
        ]);
    }

    public function updateStatus(Account $account, string $status): bool
    {
        return $account->update(['status' => $status]);
    }
}
