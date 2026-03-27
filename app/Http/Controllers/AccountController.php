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
        $account->load('coHolders');
        
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
            'minor_id' => 'required_if:type,minor|nullable|exists:users,id',
        ]);

        $account = $this->accountService->createAccount($request->user(), $validated);

        return response()->json([
            'message' => 'Account created successfully',
            'data' => $account
        ], 201);
    }

    /**
     * Add a co-holder to an existing account.
     */
    
    public function addCoHolder(Request $request, Account $account)
    {
        // Only owners (or the original creator) can add other co-holders
        
        $isOwner = $account->coHolders()->where('user_id', $request->user()->id)->whereIn('role', ['owner'])->exists();
        if (!$isOwner) {
            return response()->json(['message' => 'Unauthorized. Only owners can add co-holders.'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'sometimes|string|in:owner,co_holder,minor',
        ]);

        $role = $validated['role'] ?? 'co_holder';
        $userToAdd = \App\Models\User::findOrFail($validated['user_id']);

        $this->accountService->addCoHolder($account, $userToAdd, $role);

        return response()->json([
            'message' => 'Co-holder added successfully',
        ], 200);
    }
}
