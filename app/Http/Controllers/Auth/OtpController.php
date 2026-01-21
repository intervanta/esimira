<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterOtpMail;
use App\Models\Customer;
use App\Models\Referral;
use App\Models\Otp;
use App\Services\TrustedDeviceService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    protected $trustedDeviceService;
    protected walletService $walletService;

    public function __construct(TrustedDeviceService $trustedDeviceService, WalletService $walletService)
    {
        $this->trustedDeviceService = $trustedDeviceService;
        $this->walletService = $walletService;
    }

    /**
     * VERIFY OTP + REGISTER + REFERRAL LOGIC
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:4|numeric',
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'referral_code' => 'nullable|string|max:20|regex:/^[A-Z0-9]+$/',
            'promotional_emails' => 'boolean',
            'remember_me' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate OTP
        $otpRecord = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP. Please try again.'
            ], 422);
        }

        // Check duplicate email
        if (Customer::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already registered. Please login instead.'
            ], 422);
        }

        try {

            /**
             * ===========================
             *   REFERRAL LOGIC - MULTI-SOURCE
             * ===========================
             */

            // PRIORITY: 1. Input field > 2. URL session > 3. Cookie
            $referredByCode = null;
            $referralSource = 'none';

            // Using switch-like logic for priority system
            switch (true) {
                case $request->filled('referral_code'):
                    $referredByCode = strtoupper(trim($request->referral_code));
                    $referralSource = 'input';
                    Log::info('Referral code from input field', ['code' => $referredByCode]);
                    break;

                case Session::has('referral.code'):
                    $referredByCode = Session::get('referral.code');
                    $referralSource = 'url';
                    Log::info('Referral code from URL session', ['code' => $referredByCode]);
                    break;

                case $request->hasCookie('referral_code'):
                    $referredByCode = $request->cookie('referral_code');
                    $referralSource = 'cookie';
                    Log::info('Referral code from cookie', ['code' => $referredByCode]);
                    break;

                default:
                    $referredByCode = null;
                    $referralSource = 'none';
                    break;
            }

            $referrer = null;
            $isValidReferral = false;

            if ($referredByCode) {
                $referrer = Customer::where('referral_code', $referredByCode)->first();
                
                if ($referrer) {
                    // Prevent self-referral
                    switch ($referrer->email === $request->email) {
                        case true:
                            Log::warning('Self-referral attempt prevented', ['email' => $request->email]);
                            break;
                        case false:
                            $isValidReferral = true;
                            Log::info('Valid referral code found', [
                                'code' => $referredByCode, 
                                'referrer_id' => $referrer->id,
                                'source' => $referralSource
                            ]);
                            break;
                    }
                } else {
                    Log::warning('Invalid referral code used', [
                        'code' => $referredByCode, 
                        'source' => $referralSource
                    ]);
                }
            }

            $userLocale = Session::get('user_locale', config('app.locale'));

            /**
             * ===========================
             *   CREATE NEW CUSTOMER
             * ===========================
             */
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'locale' => $userLocale,
                'promotional_emails' => $request->boolean('promotional_emails'),
                'referral_code' => Customer::generateReferralCode($request->name),
                'referred_by' => $isValidReferral ? $referrer->id : null,
            ]);

            /**
             * ===========================
             *   CREATE REFERRAL RECORD
             * ===========================
             */
            if ($isValidReferral) {
                Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_user_id' => $customer->id,
                    'referral_code' => $referredByCode,
                    'referral_source' => $referralSource,
                    'event' => 'signup',
                    'reward_amount' => null, 
                    'status' => 'pending', 
                    'meta' => json_encode([
                        'signup_date' => now()->toDateTimeString(),
                        'referral_source' => $referralSource,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]),
                ]);

                Log::info('Referral record created with pending status', [
                    'referrer_id' => $referrer->id,
                    'referred_user_id' => $customer->id,
                    'code' => $referredByCode
                ]);
            }

            // Clean up referral data regardless of source
            Session::forget('referral.code');
            Session::forget('referral.captured_at');
            Session::forget('referral.source_url');
            
            // Delete used OTP
            $otpRecord->delete();

            /**
             * ==================================
             *        LOGIN & TRUST DEVICE
             * ==================================
             */
            Auth::login($customer);

            app()->setLocale($userLocale);
            Session::put('locale', $userLocale);
            Session::put('user_locale', $userLocale);

            try {
                $rememberDevice = $request->boolean('remember_me', true);
                $this->trustedDeviceService->addTrustedDevice($customer, $request, $rememberDevice);
            } catch (Exception $e) {
                Log::error('Trusted device add failed: ' . $e->getMessage());
            }

            $request->session()->regenerate();

            // Prepare response
            $responseData = [
                'success' => true,
                'message' => 'Registration successful! Redirecting...',
                'redirect' => route('dashboard'),
                'device_trusted' => true,
            ];

            // Add referral info to response if applicable
            if ($isValidReferral) {
                $responseData['referral_applied'] = true;
                $responseData['referral_source'] = $referralSource;
                $responseData['referrer_name'] = $referrer->name;
                $responseData['referral_message'] = 'Referral bonus will be applied after your first purchase!';
            }

            $response = response()->json($responseData);

            // Clear referral cookie after successful registration
            if ($request->hasCookie('referral_code')) {
                $response->withCookie(cookie()->forget('referral_code'));
            }

            return $response;

        } catch (Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * RESEND OTP
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $otp = rand(1000, 9999);

            Otp::updateOrCreate(
                ['email' => $request->email],
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(10),
                ]
            );

            Log::info('OTP resent', ['email' => $request->email]);

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
                'message' => 'New OTP sent to your email!'
            ]);
        } catch (Exception $e) {
            Log::error('OTP resend failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * VALIDATE REFERRAL CODE (Optional: For real-time validation)
     */
    public function validateReferral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral_code' => 'required|string|max:20|regex:/^[A-Z0-9]+$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid referral code format'
            ], 422);
        }

        $referralCode = strtoupper(trim($request->referral_code));
        $referrer = Customer::where('referral_code', $referralCode)->first();

        if (!$referrer) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid referral code'
            ], 422);
        }

        // Prevent self-referral (optional - check if user is logged in)
        if (Auth::check() && Auth::user()->email === $referrer->email) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot use your own referral code'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Valid referral code',
            'referrer_name' => $referrer->name
        ]);
    }
}