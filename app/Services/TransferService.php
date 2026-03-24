<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferService
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
// Perform an atomic transfer between two accounts.

    public function transfer(Account $source, Account $destination, float $amount): Transfer
    {
        return DB::transaction(function () use ($source, $destination, $amount) {
            
         
            $transfer = Transfer::create([
                'source_account_id' => $source->id,
                'destination_account_id' => $destination->id,
                'amount' => $amount,
                'status' => 'completed',
            ]);

            $this->transactionService->withdraw($source, $amount, $transfer->id);

            $this->transactionService->deposit($destination, $amount, $transfer->id);

            return $transfer;
        });
    }
}
