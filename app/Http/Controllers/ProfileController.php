<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'contact_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile'
            ], 500);
        }
    }

    public function updatePromoEmails(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'promotional_emails' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ], 422);
        }

        try {
            $user->update([
                'promotional_emails' => $request->promotional_emails
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences'
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'current_password' => ['The current password is incorrect.']
                ]
            ], 422);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password),
                'password_changed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password'
            ], 500);
        }
    }
}