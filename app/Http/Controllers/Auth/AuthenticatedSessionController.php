<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Services\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    protected $trustedDeviceService;

    public function __construct(TrustedDeviceService $trustedDeviceService)
    {
        $this->trustedDeviceService = $trustedDeviceService;
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $user = Auth::user();

        // Set user locale (your existing code)
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
        try {
            // Check if device is already trusted
            $isDeviceTrusted = $this->trustedDeviceService->isDeviceTrusted($user, $request);
            
            // Add device as trusted if "remember me" is checked OR it's a new login
            if ($request->filled('remember') || !$isDeviceTrusted) {
                $this->trustedDeviceService->addTrustedDevice($user, $request, $request->filled('remember'));
                
                Log::info('Trusted device added', [
                    'user_id' => $user->id,
                    'device_id' => $this->trustedDeviceService->generateDeviceId($request),
                    'remember' => $request->filled('remember')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to process trusted device', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            // Continue with login even if trusted device processing fails
        }

        $request->session()->regenerate();

        // Check if request expects JSON (AJAX)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => RouteServiceProvider::HOME,
                'device_trusted' => $isDeviceTrusted ?? false,
            ]);
        }

        // Default redirect for normal form submit
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Store current locale before logout to maintain it for guest session
        $currentLocale = app()->getLocale();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Restore the locale after logout for guest session
        Session::put('user_locale', $currentLocale);
        app()->setLocale($currentLocale);

        return redirect('/');
    }
}