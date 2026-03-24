<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Exception;

class TransferController extends Controller
{
    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * Handle a transfer between two accounts.
     */
    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'source_account_id' => 'required|uuid|exists:accounts,id',
            'destination_account_id' => 'required|uuid|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $source = Account::findOrFail($validated['source_account_id']);
        $destination = Account::findOrFail($validated['destination_account_id']);

        // Basic authorization check
        if ($source->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $transfer = $this->transferService->transfer($source, $destination, $validated['amount']);

            return response()->json([
                'message' => 'Transfer completed successfully',
                'data' => $transfer
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
