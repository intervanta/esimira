<div id="checkout-auth-modal" class="fixed inset-0 z-[9999] hidden" data-rocket-ignore>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" onclick="closeCheckoutAuthModal()"></div>

    <!-- Modal Container - Perfect Center -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200"
            onclick="event.stopPropagation()">

            <!-- Close Button -->
            <button onclick="closeCheckoutAuthModal()"
                class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-700 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- STEP 1: Login / Register -->
            <div id="auth-step-form" class="px-8 pt-10 pb-8">

                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900">Complete Your Purchase</h3>
                    <p class="text-sm text-gray-600 mt-2">Sign in or create an account to continue</p>
                </div>

                <!-- Global Error -->
                <div id="checkout-global-error"
                    class="hidden mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <span id="global-error-message"></span>
                </div>

                <!-- Social Login -->
                <div class="space-y-3 mb-6">
                    <a href="{{ route('social.redirect', 'google') }}?redirect=checkout"
                        class="w-full flex items-center justify-center gap-3 px-5 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 font-medium">
                        <img src="{{ asset('assets/images/75_12487.svg') }}" alt="Google" class="w-5 h-5"> Continue
                        with Google
                    </a>
                    <a href="{{ route('social.redirect', 'facebook') }}?redirect=checkout"
                        class="w-full flex items-center justify-center gap-3 px-5 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 font-medium">
                        <img src="{{ asset('assets/images/75_12502.svg') }}" alt="Facebook" class="w-5 h-5"> Continue
                        with Facebook
                    </a>
                </div>

                <div class="flex items-center mb-6">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-sm text-gray-500 font-medium bg-white">OR</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <!-- Login Form -->
                <form id="checkout-login-form" onsubmit="handleCheckoutLogin(event)" class="space-y-5">
                    @csrf
                    <input type="email" name="email" placeholder="Email address" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    <div class="relative">
                        <input type="password" id="checkout-login-password" name="password" placeholder="Password"
                            required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <button type="button" onclick="toggleCheckoutPasswordVisibility()"
                            class="absolute right-3 top-3.5 text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <button type="submit" id="checkout-login-btn"
                        class="w-full btn btn-primary text-white font-bold py-4 rounded-xl transition">
                        Sign In
                    </button>
                </form>

                <!-- Register Form - NO type="submit" -->
                <!-- Register Form -->
                <div id="checkout-register-form" class="hidden space-y-5">
                    @csrf

                    <div>
                        <input type="text" id="reg-name" placeholder="Full name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <div id="error-name" class="field-error text-red-600 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <input type="email" id="reg-email" placeholder="Email address" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <div id="error-email" class="field-error text-red-600 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="relative">
                        <input type="password" id="reg-password" placeholder="Create password" required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <button type="button" onclick="toggleCheckoutRegisterPasswordVisibility()"
                            class="absolute right-3 top-3.5 text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>
                        </button>
                        <div id="error-password" class="field-error text-red-600 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <input type="password" id="reg-confirm" placeholder="Confirm password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <div id="error-password_confirmation" class="field-error text-red-600 text-sm mt-1 hidden">
                        </div>
                    </div>

                    <div>
                        <input type="text" id="reg-referral" placeholder="Referral code (optional)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <div id="error-referrer_code" class="field-error text-red-600 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="flex items-start gap-3 text-xs text-gray-600">
                        <input type="checkbox" id="reg-terms" required class="mt-1">
                        <label for="reg-terms">I agree to the <a href="/terms" target="_blank"
                                class="text-indigo-600 underline">Terms</a> and <a href="/privacy" target="_blank"
                                class="text-indigo-600 underline">Privacy Policy</a></label>
                    </div>
                    <div id="error-terms" class="field-error text-red-600 text-sm hidden"></div>

                    <button type="button" onclick="handleRegisterClick()" id="checkout-register-btn"
                        class="w-full btn btn-primary text-white font-bold py-4 rounded-xl transition">
                        Create Account
                    </button>
                </div>

                <!-- Switch Links -->
                <div class="mt-8 text-center text-sm">
                    <span class="text-gray-600" id="switch-text">Don't have an account?</span>
                    <button type="button" onclick="showRegisterForm()" id="switch-to-register"
                        class="ml-2 text-indigo-600 font-semibold hover:underline">Sign up</button>
                    <button type="button" onclick="showLoginForm()" id="switch-to-login"
                        class="hidden ml-2 text-indigo-600 font-semibold hover:underline">Sign in</button>
                </div>
            </div>

            <!-- STEP 2: OTP Verification -->
            <div id="auth-step-otp" class="hidden px-8 pt-10 pb-8 text-center">
                <div class="mb-8">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold">Verify Your Email</h3>
                    <p class="text-sm text-gray-600 mt-2">We sent a 4-digit code to</p>
                    <p id="otp-email-display" class="font-bold text-lg mt-1"></p>
                </div>

                <input type="text" id="checkout-otp-input" maxlength="4" inputmode="numeric"
                    class="w-full text-center text-5xl font-bold tracking-widest px-4 py-6 border-2 rounded-2xl focus:border-indigo-500 outline-none mb-6"
                    placeholder="0000"
                    oninput="this.value=this.value.replace(/\D/g,''); if(this.value.length===4) handleCheckoutVerifyOtp()">

                <div id="otp-error" class="hidden text-red-600 font-medium mb-4"></div>
                <div id="otp-success" class="hidden text-green-600 font-medium mb-4">Verified! Redirecting...</div>

                <button type="button" onclick="handleCheckoutVerifyOtp()" id="verify-otp-btn"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl">
                    Verify & Continue
                </button>

                <div class="mt-4 text-sm text-gray-600">
                    Didn't receive? <button type="button" onclick="handleCheckoutResendOtp()"
                        class="text-indigo-600 font-medium hover:underline">Resend code</button>
                </div>

                <button type="button" onclick="backToAuthForm()" class="mt-6 text-gray-500 text-sm">
                    ← Back
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    // Global State
    let checkoutCurrentEmail = '';

    // ====================== MODAL CONTROL ======================
    function showCheckoutAuthModal() {
        document.getElementById('checkout-auth-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('auth-step-form').classList.remove('hidden');
        document.getElementById('auth-step-otp').classList.add('hidden');
        document.getElementById('checkout-global-error').classList.add('hidden');
        showLoginForm();
    }

    function closeCheckoutAuthModal() {
        document.getElementById('checkout-auth-modal').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('checkout-otp-input').value = '';
        document.getElementById('otp-error').classList.add('hidden');
        document.getElementById('otp-success').classList.add('hidden');
    }

    // ====================== FORM SWITCH ======================
    function showLoginForm() {
        document.getElementById('checkout-login-form').classList.remove('hidden');
        document.getElementById('checkout-register-form').classList.add('hidden');
        document.getElementById('switch-to-register').classList.remove('hidden');
        document.getElementById('switch-to-login').classList.add('hidden');
        document.getElementById('switch-text').textContent = "Don't have an account?";
    }

    function showRegisterForm() {
        document.getElementById('checkout-login-form').classList.add('hidden');
        document.getElementById('checkout-register-form').classList.remove('hidden');
        document.getElementById('switch-to-register').classList.add('hidden');
        document.getElementById('switch-to-login').classList.remove('hidden');
        document.getElementById('switch-text').textContent = "Already have an account?";
    }

    // ====================== OTP STEP ======================
    function showOtpStep(email) {
        checkoutCurrentEmail = email;
        document.getElementById('auth-step-form').classList.add('hidden');
        document.getElementById('auth-step-otp').classList.remove('hidden');
        document.getElementById('otp-email-display').textContent = email;
        document.getElementById('checkout-otp-input').focus();
        document.getElementById('checkout-otp-input').value = '';
        document.getElementById('otp-error').classList.add('hidden');
    }

    function backToAuthForm() {
        document.getElementById('auth-step-otp').classList.add('hidden');
        document.getElementById('auth-step-form').classList.remove('hidden');
    }

    // ====================== PASSWORD TOGGLE ======================
    function toggleCheckoutPasswordVisibility() {
        const field = document.getElementById('checkout-login-password');
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    function toggleCheckoutRegisterPasswordVisibility() {
        const field = document.getElementById('reg-password');
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    // ====================== LOADING STATE ======================
    function setLoading(btnId, loading = true) {
        const btn = document.getElementById(btnId);
        if (!btn) return;

        if (loading) {
            btn.disabled = true;
            btn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
            </svg>
            Processing...
        `;
        } else {
            btn.disabled = false;
            if (btnId === 'checkout-login-btn') btn.innerHTML = 'Sign In';
            if (btnId === 'checkout-register-btn') btn.innerHTML = 'Create Account';
            if (btnId === 'verify-otp-btn') btn.innerHTML = 'Verify & Continue';
        }
    }

    // ====================== LOGIN ======================
    async function handleCheckoutLogin(e) {
        e.preventDefault();
        setLoading('checkout-login-btn', true);
        document.getElementById('checkout-global-error').classList.add('hidden');

        const formData = new FormData(e.target);

        try {
            const res = await fetch('/checkout/login', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                closeCheckoutAuthModal();
                window.location.href = data.redirect || '/checkout';
            } else {
                document.getElementById('global-error-message').textContent = data.message ||
                    'Invalid email or password';
                document.getElementById('checkout-global-error').classList.remove('hidden');
            }
        } catch (err) {
            console.error('Login error:', err);
            document.getElementById('global-error-message').textContent = 'Network error';
            document.getElementById('checkout-global-error').classList.remove('hidden');
        }

        setLoading('checkout-login-btn', false);
    }

    // ====================== REGISTER (PURE JS - NO FORM SUBMIT) ======================
    async function handleRegisterClick() {
        // Clear previous errors
        document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        document.getElementById('checkout-global-error').classList.add('hidden');

        const name = document.getElementById('reg-name').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const password = document.getElementById('reg-password').value;
        const confirm = document.getElementById('reg-confirm').value;
        const referral = document.getElementById('reg-referral').value.trim();
        const terms = document.getElementById('reg-terms').checked;

        setLoading('checkout-register-btn', true);

        try {
            const res = await fetch('/checkout/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name,
                    email,
                    password,
                    password_confirmation: confirm,
                    referrer_code: referral,
                    terms: terms ? 1 : 0 // Send as boolean
                })
            });

            const data = await res.json();

            if (data.success) {
                showOtpStep(email);
            } else {
                // SHOW FIELD-SPECIFIC ERRORS
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = document.getElementById(`error-${field}`);
                        if (errorEl) {
                            errorEl.textContent = data.errors[field];
                            errorEl.classList.remove('hidden');
                        }
                    });
                }

                // Global message
                if (data.message) {
                    document.getElementById('global-error-message').textContent = data.message;
                    document.getElementById('checkout-global-error').classList.remove('hidden');
                }
            }
        } catch (err) {
            console.error(err);
            document.getElementById('global-error-message').textContent = 'Network error';
            document.getElementById('checkout-global-error').classList.remove('hidden');
        }

        setLoading('checkout-register-btn', false);
    }

    // ====================== OTP VERIFY ======================
    async function handleCheckoutVerifyOtp() {
        const otp = document.getElementById('checkout-otp-input').value.trim();

        if (otp.length !== 4) {
            document.getElementById('otp-error').textContent = 'Please enter 4-digit code';
            document.getElementById('otp-error').classList.remove('hidden');
            return;
        }

        setLoading('verify-otp-btn', true);
        document.getElementById('otp-error').classList.add('hidden');

        try {
            const res = await fetch('/checkout/otp/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: checkoutCurrentEmail,
                    otp
                })
            });

            const data = await res.json();

            if (data.success) {
                document.getElementById('otp-success').classList.remove('hidden');
                setTimeout(() => {
                    closeCheckoutAuthModal();
                    window.location.href = data.redirect || '/checkout';
                }, 1200);
            } else {
                document.getElementById('otp-error').textContent = data.message || 'Invalid or expired OTP';
                document.getElementById('otp-error').classList.remove('hidden');
                document.getElementById('checkout-otp-input').value = '';
            }
        } catch (err) {
            console.error('OTP error:', err);
            document.getElementById('otp-error').textContent = 'Network error';
            document.getElementById('otp-error').classList.remove('hidden');
        }

        setLoading('verify-otp-btn', false);
    }

    // ====================== RESEND OTP ======================
    async function handleCheckoutResendOtp() {
        try {
            const res = await fetch('/checkout/otp/resend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: checkoutCurrentEmail
                })
            });
            const data = await res.json();
            alert(data.message || 'New OTP sent!');
        } catch (err) {
            alert('Failed to resend OTP');
        }
    }

    // ====================== EXPOSE GLOBALLY ======================
    window.showCheckoutAuthModal = showCheckoutAuthModal;
    window.closeCheckoutAuthModal = closeCheckoutAuthModal;
    window.showLoginForm = showLoginForm;
    window.showRegisterForm = showRegisterForm;
    window.handleCheckoutLogin = handleCheckoutLogin;
    window.handleRegisterClick = handleRegisterClick;
    window.handleCheckoutVerifyOtp = handleCheckoutVerifyOtp;
    window.handleCheckoutResendOtp = handleCheckoutResendOtp;
    window.backToAuthForm = backToAuthForm;
</script>
