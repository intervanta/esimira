<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterOtpMail;
use App\Models\Customer;
use App\Models\Otp;
use App\Models\Referral;
use App\Services\TrustedDeviceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;

class CheckoutAuthController extends Controller
{
    protected $trustedDeviceService;

    public function __construct(TrustedDeviceService $trustedDeviceService)
    {
        $this->trustedDeviceService = $trustedDeviceService;
    }

    /**
     * Handle checkout registration request
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'referrer_code' => 'nullable|string|max:20',
            'terms' => 'required|accepted', // This triggers the error
        ]);

        if ($validator->fails()) {
            // Transform Laravel errors into field => message format
            $errors = [];
            foreach ($validator->errors()->messages() as $field => $messages) {
                $errors[$field] = $messages[0]; // Take first message only
            }

            return response()->json([
                'success' => false,
                'message' => 'Something needs a quick check. Please review the fields below.',
                'errors' => $errors
            ], 422);
        }

        try {
            // Check if email already exists
            if (Customer::where('email', $request->email)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already registered. Please login instead.',
                    'errors' => ['email' => ['This email is already registered.']]
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

            Log::info('Generated OTP for checkout registration', [
                'email' => $request->email,
                'otp' => $otp
            ]);

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


            // Store registration data in session for OTP verification
            session([
                'checkout_registration_data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'referrer_code' => $request->referrer_code,
                    'promotional_emails' => $request->boolean('promotional_emails'),
                    'terms' => $request->boolean('terms'),
                ]
            ]);

            Log::info('Checkout OTP sent for registration', ['email' => $request->email]);

            // In a real application, you would send the OTP via email here
            // Mail::to($request->email)->send(new OtpMail($otp));

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email!',
                'email' => $request->email
            ]);
        } catch (Exception $e) {
            Log::error('Checkout registration OTP failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Handle checkout login request
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $user = Auth::user();
                $request->session()->regenerate();

                // Set user locale
                if ($user && $user->locale) {
                    app()->setLocale($user->locale);
                    Session::put('locale', $user->locale);
                    Session::put('user_locale', $user->locale);
                } else {
                    $sessionLocale = Session::get('user_locale', config('app.locale'));
                    app()->setLocale($sessionLocale);
                    if ($user) {
                        $user->locale = $sessionLocale;
                        $user->save();
                    }
                }

                // Trusted Device Functionality
                $isDeviceTrusted = false;
                try {
                    // Check if device is already trusted
                    $isDeviceTrusted = $this->trustedDeviceService->isDeviceTrusted($user, $request);

                    // Add device as trusted if "remember me" is checked OR it's a new login
                    if ($request->filled('remember') || !$isDeviceTrusted) {
                        $this->trustedDeviceService->addTrustedDevice($user, $request, $request->filled('remember'));

                        Log::info('Checkout trusted device added', [
                            'user_id' => $user->id,
                            'device_id' => $this->trustedDeviceService->generateDeviceId($request),
                            'remember' => $request->filled('remember')
                        ]);
                    }
                } catch (Exception $e) {
                    Log::error('Checkout trusted device processing failed', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }

                Log::info('User logged in via checkout', ['email' => $request->email]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => session()->has('checkout_refill_id')
                        ? route('checkout.show') . '?refill_id=' . session('checkout_refill_id')
                        : route('dashboard'),
                    'device_trusted' => $isDeviceTrusted,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
                'errors' => ['email' => ['The provided credentials do not match our records.']]
            ], 422);
        } catch (Exception $e) {
            Log::error('Checkout login failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Login failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify OTP for checkout registration
     */
    /**
     * Verify OTP for checkout registration
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:4|numeric',
            'email' => 'required|email|max:255',
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

        // Get registration data from session
        $registrationData = session('checkout_registration_data');

        if (!$registrationData || $registrationData['email'] !== $request->email) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired. Please start over.'
            ], 422);
        }

        try {
            // Referral logic
            $referredByCode = $registrationData['referrer_code'] ?? null;
            $referralSource = 'input';
            $referrer = null;
            $isValidReferral = false;

            if ($referredByCode) {
                $referrer = Customer::where('referral_code', $referredByCode)->first();

                if ($referrer && $referrer->email !== $request->email) {
                    $isValidReferral = true;
                    Log::info('Checkout valid referral code found', [
                        'code' => $referredByCode,
                        'referrer_id' => $referrer->id,
                        'source' => $referralSource
                    ]);
                }
            }

            $userLocale = Session::get('user_locale', config('app.locale'));

            // Create new customer
            $customer = Customer::create([
                'name' => $registrationData['name'],
                'email' => $request->email,
                'password' => Hash::make($registrationData['password']),
                'email_verified_at' => now(),
                'locale' => $userLocale,
                'promotional_emails' => $registrationData['promotional_emails'],
                'referral_code' => Customer::generateReferralCode($registrationData['name']),
                'referred_by' => $isValidReferral ? $referrer->id : null,
            ]);

            // Create referral record if applicable
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
                        'user_agent' => $request->userAgent(),
                        'source' => 'checkout'
                    ]),
                ]);
            }

            // Clear session data
            session()->forget('checkout_registration_data');

            // Delete used OTP
            $otpRecord->delete();

            // Login user
            Auth::login($customer);

            app()->setLocale($userLocale);
            Session::put('locale', $userLocale);
            Session::put('user_locale', $userLocale);

            // Add trusted device
            try {
                $this->trustedDeviceService->addTrustedDevice($customer, $request, true);
            } catch (Exception $e) {
                Log::error('Checkout trusted device add failed: ' . $e->getMessage());
            }

            $request->session()->regenerate();

            // Store checkout data in session to preserve it
            $checkoutRefillId = session('checkout_refill_id');

            $responseData = [
                'success' => true,
                'message' => 'Registration successful! Redirecting...',
                'redirect' => $checkoutRefillId
                    ? route('checkout.show') . '?refill_id=' . $checkoutRefillId
                    : route('dashboard'),
                'device_trusted' => true,
            ];

            if ($isValidReferral) {
                $responseData['referral_applied'] = true;
                $responseData['referral_source'] = $referralSource;
                $responseData['referrer_name'] = $referrer->name;
                $responseData['referral_message'] = 'Referral bonus will be applied after your first purchase!';
            }

            return response()->json($responseData);
        } catch (Exception $e) {
            Log::error('Checkout registration failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Resend OTP for checkout
     */
    public function resendOtp(Request $request): JsonResponse
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

            Log::info('Checkout OTP resent', ['email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'New OTP sent to your email!'
            ]);
        } catch (Exception $e) {
            Log::error('Checkout OTP resend failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }
}
