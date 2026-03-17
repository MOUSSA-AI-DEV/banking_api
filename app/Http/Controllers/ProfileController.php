<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'message' => 'Profile fetched successfully',
            'data' => $request->user(),
        ], 200);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->fill($validated)->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user,
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
      
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
            ], 422);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully',
        ], 200);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        // Revoquer tous les tokens avant suppression
        $user->tokens()->delete(); 
        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully',
        ], 200);
    }
}