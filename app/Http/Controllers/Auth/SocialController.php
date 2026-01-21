<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Customer;
use App\Models\Referral;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Services\TrustedDeviceService;
use Illuminate\Http\Request;
use Exception;

class SocialController extends Controller
{
    protected $providers = ['google', 'facebook', 'apple'];
    protected $trustedDeviceService;

    public function __construct(TrustedDeviceService $trustedDeviceService)
    {
        $this->trustedDeviceService = $trustedDeviceService;
    }

    public function redirect($provider)
    {
        if (!in_array($provider, $this->providers)) abort(404);
        return Socialite::driver($provider)
            ->stateless()
            ->redirect();
    }

    public function callback($provider, Request $request)
    {
        if (!in_array($provider, $this->providers)) abort(404);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            /**
             * ===========================
             *   REFERRAL LOGIC - MULTI-SOURCE
             * ===========================
             */

            // PRIORITY: 1. Input parameter > 2. URL session > 3. Cookie
            $referredByCode = null;
            $referralSource = 'none';

            // Using switch-like logic for priority system
            switch (true) {
                case $request->has('referral_code'):
                    $referredByCode = strtoupper(trim($request->referral_code));
                    $referralSource = 'input';
                    Log::info('Referral code from input parameter', ['code' => $referredByCode, 'provider' => $provider]);
                    break;

                case Session::has('referral.code'):
                    $referredByCode = Session::get('referral.code');
                    $referralSource = 'url';
                    Log::info('Referral code from URL session', ['code' => $referredByCode, 'provider' => $provider]);
                    break;

                case $request->hasCookie('referral_code'):
                    $referredByCode = $request->cookie('referral_code');
                    $referralSource = 'cookie';
                    Log::info('Referral code from cookie', ['code' => $referredByCode, 'provider' => $provider]);
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
                    switch ($socialUser->getEmail() && $referrer->email === $socialUser->getEmail()) {
                        case true:
                            Log::warning('Self-referral attempt prevented in social login', [
                                'email' => $socialUser->getEmail(),
                                'provider' => $provider
                            ]);
                            break;
                        case false:
                            $isValidReferral = true;
                            Log::info('Valid referral code found in social login', [
                                'code' => $referredByCode, 
                                'referrer_id' => $referrer->id,
                                'source' => $referralSource,
                                'provider' => $provider
                            ]);
                            break;
                    }
                } else {
                    Log::warning('Invalid referral code used in social login', [
                        'code' => $referredByCode, 
                        'source' => $referralSource,
                        'provider' => $provider
                    ]);
                }
            }

            $userLocale = Session::get('user_locale', config('app.locale'));

            // Look for existing customer by provider_id OR by email (safe linking)
            $customer = Customer::where('auth_provider_name', $provider)
                ->where('auth_provider_id', $socialUser->getId())
                ->first();

            if (! $customer && $socialUser->getEmail()) {
                $customer = Customer::where('email', $socialUser->getEmail())->first();
            }
            
            $isNewCustomer = !$customer;

            if (! $customer) {
                // create new customer
                $customer = Customer::create([
                    'name' => $socialUser->getName() ?? 'No name',
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(40)),
                    'referral_code' => Customer::generateReferralCode($socialUser->getName() ?? 'No name'),
                    'promotional_emails' => false, 
                    'auth_provider_name' => $provider,
                    'auth_provider_id' => $socialUser->getId(),
                    'auth_provider_raw' => $socialUser->user,
                    'locale' => $userLocale,
                    'referred_by' => $isValidReferral ? $referrer->id : null,
                ]);

                /**
                 * ===========================
                 *   CREATE REFERRAL RECORD FOR NEW CUSTOMERS
                 * ===========================
                 */
                if ($isValidReferral) {
                    Referral::create([
                        'referrer_id' => $referrer->id,
                        'referred_id' => $customer->id,
                        'referral_code' => $referredByCode,
                        'referral_source' => $referralSource,
                        'status' => 'pending', 
                        'completed_at' => null, 
                    ]);

                    Log::info('Referral record created for social login', [
                        'referrer_id' => $referrer->id,
                        'referred_id' => $customer->id,
                        'code' => $referredByCode,
                        'provider' => $provider
                    ]);
                }

            } else {
                // keep provider info up-to-date
                $customer->update([
                    'auth_provider_name' => $provider,
                    'auth_provider_id' => $socialUser->getId(),
                    'auth_provider_raw' => $socialUser->user,
                    'locale' => $userLocale,
                ]);
            }

            Auth::guard('web')->login($customer, true);

            /**
             * ===========================
             *   TRUSTED DEVICE MANAGEMENT
             * ===========================
             */
            try {
                $rememberDevice = $request->boolean('remember_me', true);
                $this->trustedDeviceService->addTrustedDevice($customer, $request, $rememberDevice);
                Log::info('Trusted device added for social login', [
                    'customer_id' => $customer->id,
                    'provider' => $provider,
                    'remember_device' => $rememberDevice
                ]);
            } catch (Exception $e) {
                Log::error('Trusted device add failed: ' . $e->getMessage(), [
                    'customer_id' => $customer->id,
                    'provider' => $provider
                ]);
                // Continue with login even if trusted device fails
            }

            /**
             * ===========================
             *   CLEAN UP REFERRAL DATA
             * ===========================
             */
            if ($isNewCustomer) {
                Session::forget('referral.code');
                Session::forget('referral.captured_at');
                Session::forget('referral.source_url');
            }

            // Prepare redirect with referral message if applicable
            $redirect = redirect()->intended('/dashboard');
            
            if ($isNewCustomer && $isValidReferral) {
                // Add flash message for referral bonus info
                Session::flash('referral_message', 'Referral bonus will be applied after your first purchase!');
            }

            // Clear referral cookie after successful registration
            if ($request->hasCookie('referral_code')) {
                $redirect->withCookie(cookie()->forget('referral_code'));
            }

            return $redirect;

        } catch (\Exception $e) {
            Log::error('Social login failed: ' . $e->getMessage(), [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            
            return redirect('/login')->with('error', 'Social login failed. Please try again.');
        }
    }

    /**
     * Social login with referral code parameter
     */
    public function redirectWithReferral($provider, Request $request)
    {
        if (!in_array($provider, $this->providers)) abort(404);

        // Store referral code from query parameter if provided
        switch (true) {
            case $request->has('ref'):
                Session::put('referral.code', strtoupper(trim($request->ref)));
                break;
                
            case $request->has('code'):
                Session::put('referral.code', strtoupper(trim($request->code)));
                break;
                
            case $request->has('referral'):
                Session::put('referral.code', strtoupper(trim($request->referral)));
                break;
        }

        return Socialite::driver($provider)
            ->stateless()
            ->redirect();
    }
}