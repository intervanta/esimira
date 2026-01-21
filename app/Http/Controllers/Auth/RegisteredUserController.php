<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterOtpMail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'password_confirmation' => 'required',
            'referral_code' => 'nullable|string|max:50|exists:referrals,code',
            'promotional_emails' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate OTP
        $otp = rand(1000, 9999);

        // Store OTP
        Otp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

         try {
                Mail::to($request->email)->send(new RegisterOtpMail($otp, $request->name));

                Log::info('Checkout OTP email SENT successfully', [
                    'email' => $request->email
                ]);
            } catch (\Exception $e) {
                Log::error('Checkout OTP email FAILED to send', [
                    'email' => $request->email,
                    'error' => $e->getMessage()
                ]);
            }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email'
        ]);
    }
}