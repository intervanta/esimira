<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Esimira</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --color-white: #ffffff;
            --color-primary: #f4633a;
            --color-text-dark: #333333;
            --color-text-light: #888888;
            --color-text-medium: #666666;
            --color-text-black: #000000;
            --color-link: #007bff;
            --color-border-light: #dddddd;
            --color-border-medium: #cccccc;
            --color-border-dark: #333333;
            --bg-light: #f9f9f9;
            --font-primary: 'Roboto', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--color-white);
        }

        .signin-page {
            display: flex;
            max-width: 100%;
            min-height: 100vh;
            margin: 0 auto;
            background-color: var(--color-white);
            overflow: hidden;
        }

        .signin-promo {
            flex: 0 0 644px;
            position: relative;
            padding: 30px 62px;
            background: linear-gradient(137deg, #f4633a 0%, #8e3a22 137.24%);
            color: var(--color-white);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .promo-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.15;
            transform: rotate(180deg);
            overflow: hidden;
        }
        
        .merged-bg-images {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .bg-img-1, .bg-img-2 {
            position: absolute;
            left: -467.29px;
            width: auto;
            height: auto;
        }
        
        .bg-img-1 {
            top: 12.82px;
        }
        
        .bg-img-2 {
            top: 469.94px;
            transform: rotate(180deg);
        }

        .promo-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .promo-header {
            margin-bottom: 173px;
        }

        .logo {
            position: relative;
            width: 112px;
            height: 37px;
        }

        .features-list {
            display: flex;
            flex-direction: column;
            gap: 90px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .feature-icon-wrapper {
            flex-shrink: 0;
            width: 70px;
            height: 70px;
            background-color: rgba(255, 255, 255, 0.33);
            border: 1px solid rgba(255, 255, 255, 0.33);
            border-radius: 10px;
            backdrop-filter: blur(1px);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .text-decoration{
            text-decoration: none;
        }
        .feature-item:nth-child(3) .feature-icon-wrapper {
            padding: 16px 19px;
        }

        .icon-group {
            position: relative;
            width: 40px;
            height: 40px;
        }

        .feature-text h3 {
            margin: 0 0 6px;
            font-family: var(--font-primary);
            font-weight: 700;
            font-size: 18px;
            line-height: 1.35;
            letter-spacing: 0.18px;
        }

        .feature-text p {
            margin: 0;
            font-family: var(--font-primary);
            font-weight: 400;
            font-size: 14px;
            line-height: 1.28;
            letter-spacing: 0.14px;
        }
        
        .feature-item:nth-child(3) .feature-text p {
            line-height: 1.42;
        }

        .signin-form-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header p {
            margin: 0 0 0px;
            font-family: var(--font-primary);
            font-weight: 500;
            font-size: 24px;
            line-height: 1.35;
        }

        .form-header h1 {
            margin: 0;
            font-family: var(--font-primary);
            font-weight: 900;
            font-size: 30px;
            line-height: 1.35;
        }

        .signin-form {
            display: flex;
            flex-direction: column;
            margin-bottom: 30px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 15px;
        }

        .input-group label {
            font-family: var(--font-primary);
            font-weight: 400;
            font-size: 16px;
            line-height: 1;
            color: var(--color-text-dark);
        }

        .input-group input {
            height: 48px;
            padding: 0 16px;
            border: 1px solid var(--color-border-light);
            border-radius: 8px;
            background-color: var(--bg-light);
            font-size: 16px;
            font-family: var(--font-primary);
            color: var(--color-text-dark);
        }
        
        .input-group input::placeholder {
            color: var(--color-text-light);
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .password-wrapper input {
            width: 100%;
            padding-right: 45px;
        }
        
        .password-toggle {
            position: absolute;
            right: 14px;
            cursor: pointer;
        }

        .forgot-password {
            align-self: flex-end;
            margin-top: -2px;
            margin-bottom: 30px;
            font-family: var(--font-primary);
            font-weight: 400;
            font-size: 16px;
            line-height: 1;
            color: var(--color-link);
            text-decoration: none;
        }

        .btn {
            height: 52px;
            border-radius: 50px;
            font-size: 20px;
            font-weight: 400;
            font-family: var(--font-primary);
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--color-primary);
            color: var(--color-white);
        }
        
        .btn-primary:hover {
            background-color: #e05530;
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .separator {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 30px 0;
        }
        
        .separator hr {
            flex: 1;
            border: none;
            border-top: 1px solid var(--color-border-medium);
        }
        
        .separator span {
            font-family: var(--font-primary);
            font-weight: 400;
            font-size: 16px;
            color: var(--color-text-medium);
            letter-spacing: 0.16px;
        }

        .social-login {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }
        
.btn-social {
    display: grid;
    grid-template-columns: 30px 175px 0px;
    align-items: center;
     justify-content: center !important;
    padding: 12px 16px;
    border: 1px solid var(--color-border-dark);
    border-radius: 50px;
    background-color: transparent;
    font-family: var(--font-primary);
    font-size: 16px;
    color: var(--color-text-black);
    text-decoration: none;
}

        
        .btn-social:hover {
            background-color: #f5f5f5;
        }
        
     .btn-social img {
    width: 26px;
    height: 26px;
    justify-self: center; /* icon fixed position */
}
.btn-social span {
    justify-content: center;   /* text perfectly centered */
    margin-left: 12px;
}

        .signup-link {
            text-align: center;
            font-family: var(--font-primary);
            font-size: 18px;
            color: var(--color-text-black);
            margin: 0;
        }
        
        .signup-link a {
            color: var(--color-link);
            font-weight: 400;
            text-decoration: none;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .general-error {
            color: #dc3545;
            font-size: 16px;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 5px;
        }
        
        .validation-errors {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .validation-errors ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        
        .validation-errors li {
            color: #721c24;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .signin-header {
            display: none;
        }

        @media (max-width: 1024px) {
            .signin-page {
                flex-direction: column;
            }
            
            .signin-promo {
                flex: 0 0 auto;
                height: auto;
                padding: 40px 30px;
            }
            
            .promo-header {
                margin-bottom: 80px;
            }
            
            .features-list {
                gap: 40px;
            }
            
            .signin-form-section {
                padding: 60px 30px;
            }
        }

        @media (max-width: 767px) {
            .signin-header {
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                padding: 12px 0;
                background: #fff;
                z-index: 10;
                border-bottom: 1px solid #eee;
            }
            
            .desktop-view {
                display: none;
            }

            .signin-header .back-btn {
                position: absolute;
                left: 16px;
                background: none;
                border: none;
                padding: 0;
                cursor: pointer;
                display: flex;
                align-items: center;
            }

            .signin-header .back-btn img {
                width: 28px;
                height: 28px;
                object-fit: contain;
            }

            .signin-header .logo img {
                height: 40px;
                object-fit: contain;
            }
            
            .signin-page {
                flex-direction: column;
            }

            .signin-form-section {
                order: 1;
            }

            .signin-promo {
                order: 2;
            }

            .signin-promo,
            .signin-form-section {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <section id="section-signin">
        <header class="signin-header">
            <button class="back-btn" onclick="history.back()">
                <img src="{{ asset('assets/images/back-btn.png') }}" alt="Back" />
            </button>

            <div class="logo">
                <img src="{{ asset('assets/images/Frame-logo.png') }}" alt="Esimira Logo" />
            </div>
        </header>

        <main class="signin-page">
            <div class="signin-promo">
                <div class="promo-bg-pattern">
                    <div class="merged-bg-images">
                        <img src="{{ asset('assets/images/75_11492.svg') }}" alt="background pattern" class="bg-img-1">
                        <img src="{{ asset('assets/images/75_11939.svg') }}" alt="background pattern" class="bg-img-2">
                    </div>
                </div>
                <div class="promo-content">
                    <header class="promo-header">
                        <div class="logo desktop-view">
                            <img src="{{ asset('assets/images/75_12420.svg') }}" alt="Esimira Logo" style="position: absolute; top: 0px; left: 0px;">
                            <img src="{{ asset('assets/images/75_12421.svg') }}" alt="Esimira Logo" style="position: absolute; top: 6.2080078125px; left: 34.56640625px;">
                            <img src="{{ asset('assets/images/75_12432.svg') }}" alt="Esimira Logo" style="position: absolute; top: 27.4765625px; left: 34.4296875px;">
                        </div>
                    </header>
                    <div class="features-list">
                        <div class="feature-item">
                            <div class="feature-icon-wrapper">
                                <img src="{{ asset('assets/images/75_12390.svg') }}" alt="Secure Payments Icon">
                            </div>
                            <div class="feature-text">
                                <h3>Secure Payments</h3>
                                <p>We prioritize your security with advanced payment protection, keeping your financial details safe at every step.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon-wrapper">
                                <div class="icon-group">
                                    <img src="{{ asset('assets/images/75_12401.svg') }}" alt="Resident Number Icon" style="position: absolute; top: 1.6669921875px; left: 3.333984375px;">
                                    <img src="{{ asset('assets/images/75_12405.svg') }}" alt="" style="position: absolute; top: 11px; left: 19.5px;">
                                </div>
                            </div>
                            <div class="feature-text">
                                <h3>Resident Number</h3>
                                <p>Enjoy seamless communication with a local number — call and message effortlessly wherever your journey takes you.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon-wrapper">
                                <img src="{{ asset('assets/images/75_12412.svg') }}" alt="Instant Activation Icon">
                            </div>
                            <div class="feature-text">
                                <h3>Instant Activation</h3>
                                <p>Connect in seconds — no physical SIM or delays. Just scan, install, and start using your eSIM right away.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="signin-form-section">
                <div class="form-container" x-data="loginForm()">
                    <header class="form-header">
                        <p>Travel Smarter with Us</p>
                        <h1>Sign In</h1>
                    </header>
                    
                    <!-- General Error -->
                    <div x-show="generalError" x-text="generalError" class="general-error"></div>
                    {{-- <!-- Validation Errors -->
                    <template x-if="Object.keys(errors).length > 0">
                        <div class="validation-errors">
                            <ul>
                                <template x-for="(fieldErrors, key) in errors" :key="key">
                                    <template x-for="error in fieldErrors" :key="error">
                                        <li x-text="error"></li>
                                    </template>
                                </template>
                            </ul>
                        </div>
                    </template> --}}
                    <form class="signin-form" @submit.prevent="handleLogin">
                        @csrf
                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" x-model="formData.email" placeholder="Example@email.com">
                            <p x-show="errors.email" x-text="errors.email?.[0]" class="error-message"></p>
                        </div>
                        <div class="input-group">
                            <label for="password">Password</label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password" x-model="formData.password" placeholder="At least 8 characters">
                                <img src="{{ asset('assets/images/75_12475.svg') }}" alt="Show password" class="password-toggle" @click="togglePasswordVisibility">
                            </div>
                            <p x-show="errors.password" x-text="errors.password?.[0]" class="error-message"></p>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                        <button type="submit" class="btn btn-primary" x-bind:disabled="isLoading">
                            <span x-show="!isLoading">Sign In</span>
                            <span x-show="isLoading">Processing...</span>
                        </button>
                    </form>
                    <div class="separator">
                        <hr>
                        <span>OR</span>
                        <hr>
                    </div>
                    <div class="social-login">
                     <a href="{{ route('social.redirect', 'google') }}" class="btn btn-social">
    <img src="{{ asset('assets/images/75_12487.svg') }}">
    <span>Sign In with Google</span>
</a>

<a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-social">
    <img src="{{ asset('assets/images/75_12502.svg') }}">
    <span>Sign In with Facebook</span>
</a>

<a href="{{ route('social.redirect', 'apple') }}" class="btn btn-social">
    <img src="{{ asset('assets/images/a2be846863522e8be393a7ed3a097e051ec9bbfb.png') }}">
    <span>Sign In with Apple</span>
</a>


                    </div>
                    <p class="signup-link">
                        Don't you have an account? <a href="{{ route('register') }}">Sign up</a>
                    </p>
                </div>
            </div>
        </main>
    </section>

    <script>
        function loginForm() {
            return {
                isLoading: false,
                formData: {
                    email: '',
                    password: '',
                    remember: false
                },
                errors: {},
                generalError: '',
                showPassword: false,

                togglePasswordVisibility() {
                    this.showPassword = !this.showPassword;
                    const passwordField = document.getElementById('password');
                    passwordField.type = this.showPassword ? 'text' : 'password';
                },

                async handleLogin() {
                    this.isLoading = true;
                    this.errors = {};
                    this.generalError = '';

                    try {
                        const response = await fetch('{{ route('login') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            // Successful login
                            window.location.href = data.redirect || '{{ route("dashboard.index") }}';
                        } else if (data.errors) {
                            // Field validation errors
                            this.errors = data.errors;
                        } else if (data.message) {
                            // General error (wrong credentials, etc.)
                            this.generalError = data.message;
                        } else {
                            this.generalError = 'Something went wrong. Please try again.';
                        }

                    } catch (error) {
                        console.error(error);
                        this.generalError = 'Something went wrong. Please try again.';
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>