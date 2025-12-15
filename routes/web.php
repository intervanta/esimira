<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MyEsimController;
use App\Http\Controllers\AccountOrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\CheckoutAuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TrustedDeviceController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeviceCompatibilityController;
use App\Http\Controllers\ResellerController;
use App\Http\Middleware\Localization;
use App\Http\Middleware\SecureCheckout;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/preview/otp-view', function () {
    return view('emails.register_otp', ['otp' => 1234]);
});

// Language routes (MUST BE OUTSIDE localized group for global access)
Route::prefix('language')->group(function () {
    Route::post('/switch', [LanguageController::class, 'switchLang'])->name('language.switch');
    Route::get('/supported-locales', [LanguageController::class, 'getSupportedLocales'])->name('language.supported');
    Route::get('/user-preferred', [LanguageController::class, 'getUserPreferredLanguage'])->name('language.user.preferred');
    Route::get('/switch/{lang}', [LanguageController::class, 'switchLangGet'])->name('language.switch.get');
});

// Social login routes (outside localization - these are callback URLs that providers redirect to)
Route::get('auth/{provider}', [SocialController::class, 'redirect'])
    ->where('provider', 'google|facebook|apple')
    ->name('social.redirect');

Route::get('auth/{provider}/callback', [SocialController::class, 'callback'])
    ->where('provider', 'google|facebook|apple')
    ->name('social.callback');
Route::get('/api/check-auth', function () {
    return response()->json([
        'authenticated' => auth()->check()
    ]);
});
Route::post('/update-selected-refill', function (Request $request) {
    $request->validate([
        'refill_id' => 'required|exists:refills,id'
    ]);

    session(['checkout_refill_id' => $request->refill_id]);

    return response()->json(['success' => true]);
});




// Checkout specific authentication routes (outside localization)
Route::post('/checkout/register', [CheckoutAuthController::class, 'register'])->name('checkout.register');
Route::post('/checkout/login', [CheckoutAuthController::class, 'login'])->name('checkout.login');
Route::post('/checkout/otp/verify', [CheckoutAuthController::class, 'verifyOtp'])->name('checkout.otp.verify');
Route::post('/checkout/otp/resend', [CheckoutAuthController::class, 'resendOtp'])->name('checkout.otp.resend');




// OTP routes (API-like, outside localization)
Route::post('/otp/send', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::post('/otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resendOtp'])->name('otp.resend');
Route::get('/ref', [ReferralController::class, 'capture'])->name('ref.capture');
// Localized routes group - ALL application routes go here except callbacks
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localization', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/why-choose-esimira', [HomeController::class, 'whyChooseEsimira'])->name('why-choose-esimira');

    // Reseller & Business page 
    Route::get('/reseller-business', [HomeController::class, 'resellerBusiness'])->name('reseller-business');
    Route::post('/reseller/enquiry-submit', [ResellerController::class, 'submitEnquiry'])
        ->name('reseller.enquiry.submit');




    Route::get('/plans/all-data', [PlanController::class, 'getAllPlansData']);
    Route::get('/plans', [PlanController::class, 'index'])->name('plans');
    Route::get('/plans/popular-data', [PlanController::class, 'getPopularPlanData'])->name('plans.popular-data');

    Route::get('/plans/{slug}', [BundleController::class, 'show'])->name('bundles.show');
    // Home page popular plans
    Route::get('/plans/popular-data', [HomeController::class, 'getPopularPlansData']);







    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/help', [HomeController::class, 'help'])->name('help');
    // Search routes
    Route::get('/search', [SearchController::class, 'search']);




    Route::post('/switch-currency', [App\Http\Controllers\CurrencyController::class, 'switchCurrency'])->name('currency.switch');
    Route::get('/currency/rates', [App\Http\Controllers\CurrencyController::class, 'getCurrentRates'])->name('currency.rates');



    // Include auth routes INSIDE the localization group
    require __DIR__ . '/auth.php';

    // Authenticated routes
    Route::middleware(['auth'])->group(function () {

        Route::prefix('dashboard')->name('dashboard.')->group(function () {

            // Main Dashboard
            Route::get('/', [AccountController::class, 'index'])->name('index');

            // Orders
            Route::get('/orders', [AccountOrderController::class, 'index'])->name('orders');
            Route::get('/orders/{order}/receipt', [AccountOrderController::class, 'receipt'])->name('orders.receipt');

        });


          Route::get('/my-esims', [MyEsimController::class, 'index'])->name('my-esims');
          Route::get('/esim/{id}/install', [MyEsimController::class, 'install'])->name('esim.install.page');
            Route::get('/esim-details/{activation}', [MyEsimController::class, 'showDetails'])->name('esim-details');

        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('customer.profile.update');
        Route::post('/profile/promo-emails', [ProfileController::class, 'updatePromoEmails'])->name('customer.promo-emails.update');
        Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('customer.password.update');

        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
        Route::post('/checkout/process-payment', [CheckoutController::class, 'processPayment'])->name('checkout.process-payment');
        Route::get('/payment/paypal/success', [CheckoutController::class, 'paypalSuccess'])->name('payment.paypal.success');
        Route::get('/payment/paypal/cancel', [CheckoutController::class, 'paypalCancel'])->name('payment.paypal.cancel');
        Route::post('/webhook/razorpay', [CheckoutController::class, 'razorpayWebhook'])->name('webhook.razorpay');

        // Add this to your web.php file
        Route::post('/payment/verify', [CheckoutController::class, 'verify'])->name('payment.verify');
        Route::get('/order-confirmation', [CheckoutController::class, 'confirmation'])->name('order.confirmation');


        // Trusted Devices Routes
        Route::prefix('trusted-devices')->group(function () {
            Route::get('/', [TrustedDeviceController::class, 'index'])->name('trusted-devices.index');
            Route::delete('/{deviceId}', [TrustedDeviceController::class, 'destroy'])->name('trusted-devices.destroy');
            Route::delete('/', [TrustedDeviceController::class, 'removeOthers'])->name('trusted-devices.remove-others');
        });

        // Add verified middleware only where needed
        Route::middleware('verified')->group(function () {
            // Routes that require email verification
        });
    });
});
