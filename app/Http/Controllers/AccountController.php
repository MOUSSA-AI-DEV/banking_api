<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * List all accounts for the authenticated user.
     */
    public function index(Request $request)
    {
        $accounts = $request->user()->accounts;

        return response()->json([
            'message' => 'Accounts fetched successfully',
            'data' => $accounts
        ]);
    }

    /**
     * Show a specific account.
     */
    public function show(Account $account)
    {
        return response()->json([
            'message' => 'Account details fetched successfully',
            'data' => $account
        ]);
    }

    /**
     * Create a new account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:checking,savings,minor',
            'initial_balance' => 'numeric|min:0',
            'overdraft_limit' => 'nullable|numeric|min:0',
            'withdraw_limit' => 'nullable|integer|min:0',
            'guardian_id' => 'nullable|exists:users,id',
        ]);

        $account = $this->accountService->createAccount($request->user(), $validated);

        return response()->json([
            'message' => 'Account created successfully',
            'data' => $account
        ], 201);
    }
}
