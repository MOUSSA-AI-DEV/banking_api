<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Str;

class AccountService
{
    
    public function createAccount(User $user, array $data): Account
    {
        $isMinorAccount = $data['type'] === 'minor';
        
        $accountOwnerId = $isMinorAccount ? $data['minor_id'] : $user->id;
        $guardianId = $isMinorAccount ? $user->id : null;

        $account = Account::create([
            'user_id' => $accountOwnerId,
            'type' => $data['type'], // checking | savings | minor
            'status' => $data['status'] ?? 'active',
            'balance' => $data['initial_balance'] ?? 0,
            'overdraft_limit' => $data['overdraft_limit'] ?? null,
            'interest_rate' => $data['interest_rate'] ?? null,
            'withdraw_limit' => $data['withdraw_limit'] ?? null,
            'guardian_id' => $guardianId,
        ]);

        if ($isMinorAccount) {
            $account->coHolders()->attach($guardianId, ['role' => 'owner']);
            $account->coHolders()->attach($accountOwnerId, ['role' => 'minor']);
        } else {
            $account->coHolders()->attach($user->id, ['role' => 'owner']);
        }

        return $account;
    }

    public function updateStatus(Account $account, string $status): bool
    {
        return $account->update(['status' => $status]);
    }

    public function addCoHolder(Account $account, User $user, string $role = 'co_holder'): void
    {
        if (!$account->coHolders()->where('user_id', $user->id)->exists()) {
            $account->coHolders()->attach($user->id, ['role' => $role]);
        }
    }
}
