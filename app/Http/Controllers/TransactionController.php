<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Exception;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle a deposit into an account.
     */
    public function deposit(Request $request, Account $account)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $transaction = $this->transactionService->deposit($account, $validated['amount']);

        return response()->json([
            'message' => 'Deposit successful',
            'data' => $transaction
        ]);
    }

    /**
     * Handle a withdrawal from an account.
     */
    public function withdraw(Request $request, Account $account)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $transaction = $this->transactionService->withdraw($account, $validated['amount']);

            return response()->json([
                'message' => 'Withdrawal successful',
                'data' => $transaction
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
