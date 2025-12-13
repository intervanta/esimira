<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    :root {
      --primary-color: #f4633a;
      --text-light: #ffffff;
      --text-dark: #111111;
      --text-secondary: #6b7280;
      --text-tertiary: #9ca3af;
      --bg-light: #ffffff;
      --bg-input: #f9fafb;
      --border-light: #d1d5db;
      --border-medium: #e5e7eb;
      --border-dark: #374151;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      color: var(--text-dark);
      background-color: var(--bg-light);
      line-height: 1.5;
    }

    /* CSS for section section:signup */
    #signup {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .signup-container {
      display: grid;
      grid-template-columns: 1fr;
      max-width: 100%;
      width: 100%;
      min-height: 900px;
      overflow: hidden;
    }

    .info-panel {
      position: relative;
      background: linear-gradient(137deg, #f4633a 0%, #8e3a22 137.24%);
      color: var(--text-light);
      padding-left: 60px;
      padding-top: 30px;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .info-panel-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0.15;
      transform: rotate(180deg);
      overflow: hidden;
    }

    .bg-dots-wrapper {
      position: relative;
      width: 100%;
      height: 100%;
    }

    .bg-dots-wrapper img {
      position: absolute;
    }

    .bg-dot-1 {
      bottom: 12.8%;
      left: 0;
    }

    .bg-dot-2 {
      top: 12.8%;
      left: 0;
      transform: rotate(180deg);
    }

    .info-content {
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
      gap: 170px;
    }

    .logo {
      position: relative;
      width: 112px;
      height: 37px;
      cursor: pointer;
    }

    .logo img {
      position: absolute;
    }
    .logo-part-1 { top: 0; left: 0; }
    .logo-part-2 { top: 6.2px; left: 34.5px; }
    .logo-part-3 { top: 27.5px; left: 34.4px; }

    .features-list {
      display: flex;
      flex-direction: column;
      gap: 90px;
    }

    .feature-item {
      display: flex;
      align-items: flex-start;
      gap: 20px;
    }

    .feature-icon-wrapper {
      flex-shrink: 0;
      width: 70px;
      height: 70px;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: rgba(255, 255, 255, 0.33);
      border: 1px solid rgba(255, 255, 255, 0.33);
      border-radius: 10px;
      backdrop-filter: blur(1px);
    }
    
    .icon-merge-wrapper {
      position: relative;
      width: 40px;
      height: 40px;
    }

    .feature-icon-wrapper--alt-padding {
      padding: 16px 19px;
    }

    .feature-text h3 {
      margin: 0 0 6px 0;
      font-family: 'Inter', sans-serif;
      font-weight: 700;
      font-size: 18px;
      line-height: 1.35;
    }

    .feature-text p {
      margin: 0;
      font-family: 'Inter', sans-serif;
      font-weight: 400;
      font-size: 14px;
      line-height: 1.3;
      max-width: 350px;
    }

    .form-panel {
      background-color: var(--bg-light);
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
    }

    .form-wrapper {
      width: 100%;
      max-width: 450px;
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .form-header p {
      margin: 0;
      font-size: 24px;
      font-weight: 500;
      color: var(--text-dark);
    }

    .form-header h2 {
      margin: 0;
      font-size: 30px;
      font-weight: 900;
      color: var(--text-dark);
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .form-row {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      flex: 1;
    }

    .form-group label {
      font-size: 16px;
      font-weight: 400;
    }

    .form-group input {
      width: 100%;
      padding: 15px;
      border: 1px solid var(--border-light);
      border-radius: 8px;
      background-color: var(--bg-input);
      font-size: 16px;
      color: var(--text-dark);
    }

    .form-group input::placeholder {
      color: var(--text-secondary);
    }

    .password-input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .password-input-wrapper input {
      padding-right: 45px;
    }

    .password-toggle-btn {
      position: absolute;
      right: 14px;
      height: 100%;
      background: none;
      border: none;
      cursor: pointer;
    }

    .submit-btn {
      background-color: var(--primary-color);
      color: var(--text-light);
      font-size: 20px;
      font-weight: 400;
      padding: 16px;
      border-radius: 50px;
      text-align: center;
      margin-top: 15px;
      border: none;
      cursor: pointer;
    }

    .submit-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 16px;
      color: var(--text-tertiary);
    }

    .divider hr {
      flex-grow: 1;
      border: none;
      border-top: 1px solid var(--border-medium);
    }

    .social-login {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .social-btn {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 16px;
      padding: 12px;
      border: 1px solid var(--border-dark);
      border-radius: 50px;
      font-size: 16px;
      font-weight: 400;
      color: #111111;
      background: white;
      cursor: pointer;
    }

    .social-btn img {
      width: 28px;
      height: 28px;
      object-fit: contain;
    }

    .signin-link {
      text-align: center;
      font-size: 18px;
      font-family: 'Roboto', sans-serif;
      color: #122b31;
    }

    .signin-link a {
      font-weight: bold;
      color: var(--primary-color);
      text-decoration: none;
    }

    .checkbox-group {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-top: 10px;
    }

    .checkbox-group input[type="checkbox"] {
      margin-top: 3px;
      width: 18px;
      height: 18px;
    }

    .checkbox-group label {
      font-size: 14px;
      line-height: 1.4;
    }

    .checkbox-group a {
      color: var(--primary-color);
      text-decoration: underline;
    }

    .error-message {
      color: #dc2626;
      font-size: 14px;
      margin-top: 5px;
    }

    .success-message {
      color: #16a34a;
      font-size: 14px;
      margin-top: 5px;
    }

    /* OTP Modal Styles */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
    }

    .modal-content {
      background-color: white;
      width: 100%;
      max-width: 400px;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .modal-close {
      position: absolute;
      top: 16px;
      right: 16px;
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #6b7280;
    }

    .modal-title {
      font-size: 20px;
      font-weight: 600;
      text-align: center;
      margin-bottom: 8px;
    }

    .modal-subtitle {
      font-size: 14px;
      color: var(--text-secondary);
      text-align: center;
      margin-bottom: 20px;
    }

    .otp-input {
      width: 100%;
      text-align: center;
      font-size: 24px;
      padding: 12px;
      border: 1px solid var(--border-light);
      border-radius: 8px;
      margin-bottom: 16px;
      letter-spacing: 8px;
    }

    .otp-input:focus {
      outline: none;
      border-color: var(--primary-color);
    }

    .modal-actions {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .modal-btn {
      padding: 12px;
      border-radius: 8px;
      font-weight: 500;
      border: none;
      cursor: pointer;
    }

    .modal-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .modal-btn-primary {
      background-color: var(--primary-color);
      color: white;
    }

    .modal-btn-secondary {
      background-color: #e5e7eb;
      color: #374151;
    }

    .modal-btn-danger {
      background-color: #dc2626;
      color: white;
    }

    @media (min-width: 1200px) {
      .signup-container {
        grid-template-columns: 644px 1fr;
      }

      .form-row {
        flex-direction: row;
        gap: 10px;
      }
    }

    .signup-header {
      display: none;
    }

    @media (max-width: 768px) {
      .info-panel {
        order: 2;
        padding: 40px 20px;
      }
      .signup-header {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 12px 0;
        background: #fff;
        z-index: 10;
        border-bottom: 1px solid #eee;
      }
      .signup-header .back-btn {
        position: absolute;
        left: 16px;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
      }

      .signup-header .back-btn img {
        width: 28px;
        height: 28px;
        object-fit: contain;
      }
      .desktop-view {
        display: none;
      }
      .signup-header .logo img {
        height: 40px;
        object-fit: contain;
      }
      .signup-container {
        display: flex;
        flex-direction: column;
      }
      .form-panel {
        order: 1;
        padding: 40px 20px;
      }
      .info-content {
        gap: 80px;
      }
      .features-list {
        gap: 40px;
      }
    }
  </style>
</head>
<body>
  <header class="signup-header">
    <button class="back-btn" onclick="history.back()">
      <img src="../assets/images/back-btn.png" alt="Back" />
    </button>

    <div class="logo" onclick="redirectToHome()">
      <img src="../assets/images/Frame-logo.png" alt="Esimira Logo" />
    </div>
  </header>

  <section id="signup" x-data="registerForm()">
    <div class="signup-container">
      <aside class="info-panel">
        <div class="info-panel-bg">
          <!--merged image-->
          <div class="bg-dots-wrapper">
            <img src="../assets/images/75_12519.svg" alt="background pattern" class="bg-dot-1">
            <img src="../assets/images/75_12966.svg" alt="background pattern" class="bg-dot-2">
          </div>
        </div>
        <div class="info-content">
          <!--merged image-->
          <div class="logo desktop-view" onclick="redirectToHome()">
            <img src="../assets/images/75_13447.svg" alt="Esimira Logo" class="logo-part-1">
            <img src="../assets/images/75_13448.svg" alt="" class="logo-part-2">
            <img src="../assets/images/75_13459.svg" alt="" class="logo-part-3">
          </div>
          <div class="features-list">
            <article class="feature-item">
              <div class="feature-icon-wrapper">
                <img src="../assets/images/75_13417.svg" alt="Secure Payments Icon">
              </div>
              <div class="feature-text">
                <h3>Secure Payments</h3>
                <p>We prioritize your security with advanced payment protection, keeping your financial details safe at every step.</p>
              </div>
            </article>
            <article class="feature-item">
              <div class="feature-icon-wrapper">
                <!--merged image-->
                <div class="icon-merge-wrapper">
                  <img src="../assets/images/75_13428.svg" alt="Resident Number Icon" style="position: absolute; top: 1.67px; left: 3.33px;">
                  <img src="../assets/images/75_13432.svg" alt="" style="position: absolute; top: 11px; left: 19.5px;">
                </div>
              </div>
              <div class="feature-text">
                <h3>Resident Number</h3>
                <p>Enjoy seamless communication with a local number — call and message effortlessly wherever your journey takes you.</p>
              </div>
            </article>
            <article class="feature-item">
              <div class="feature-icon-wrapper feature-icon-wrapper--alt-padding">
                <img src="../assets/images/75_13439.svg" alt="Instant Activation Icon">
              </div>
              <div class="feature-text">
                <h3>Instant Activation</h3>
                <p>Connect in seconds — no physical SIM or delays. Just scan, install, and start using your eSIM right away.</p>
              </div>
            </article>
          </div>
        </div>
      </aside>

      <main class="form-panel">
        <div class="form-wrapper">
          <div class="form-header">
            <p>Become a Member With Us</p>
            <h2>Sign Up</h2>
          </div>

          <!-- Validation Errors -->
          <div x-show="Object.keys(errors).length > 0" style="background-color: #fef2f2; border: 1px solid #fecaca; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            <ul style="color: #dc2626; font-size: 14px; list-style-type: disc; padding-left: 20px;">
              <template x-for="(fieldErrors, key) in errors" :key="key">
                <template x-for="error in fieldErrors" :key="error">
                  <li x-text="error"></li>
                </template>
              </template>
            </ul>
          </div>

          <form @submit.prevent="handleRegister">
            <div class="form-row">
              <div class="form-group">
                <label for="first-name">First Name *</label>
                <input type="text" id="first-name" x-model="formData.name" placeholder="Example:- John">
                <p x-show="errors.name" x-text="errors.name?.[0]" class="error-message"></p>
              </div>
              <div class="form-group">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" placeholder="Example:- Doe">
              </div>
            </div>
            <div class="form-group">
              <label for="email">Email *</label>
              <input type="email" id="email" x-model="formData.email" placeholder="Example@email.com">
              <p x-show="errors.email" x-text="errors.email?.[0]" class="error-message"></p>
            </div>
            <div class="form-group">
              <label for="password">Password *</label>
              <div class="password-input-wrapper">
                <input type="password" id="password" x-model="formData.password" placeholder="At least 8 characters">
                <button type="button" class="password-toggle-btn" @click="togglePasswordVisibility('password')">
                  <img src="../assets/images/75_13514.svg" alt="Show password">
                </button>
              </div>
              <p class="text-secondary" style="font-size: 12px; color: #6b7280; margin-top: 4px;">Password must be at least 8 characters with uppercase, lowercase, number, and special character</p>
              <p x-show="errors.password" x-text="errors.password?.[0]" class="error-message"></p>
            </div>
            <div class="form-group">
              <label for="confirm-password">Confirm Password *</label>
              <div class="password-input-wrapper">
                <input type="password" id="confirm-password" x-model="formData.password_confirmation" placeholder="At least 8 characters">
                <button type="button" class="password-toggle-btn" @click="togglePasswordVisibility('confirm-password')">
                  <img src="../assets/images/75_13521.svg" alt="Show password">
                </button>
              </div>
              <p x-show="errors.password_confirmation" x-text="errors.password_confirmation?.[0]" class="error-message"></p>
            </div>
            <div class="form-group">
              <label for="referral-code">Referral Code</label>
              <input type="text" id="referral-code" x-model="formData.referrer_code" placeholder="Enter referral code if any">
              <p x-show="errors.referrer_code" x-text="errors.referrer_code?.[0]" class="error-message"></p>
            </div>

            <div class="checkbox-group">
              <input type="checkbox" id="terms" x-model="formData.terms">
              <label for="terms">
                I agree to the <a href="/terms">Terms of Service</a> and <a href="/privacy">Privacy Policy</a>
              </label>
            </div>
            <p x-show="errors.terms" x-text="errors.terms?.[0]" class="error-message"></p>

            <div class="checkbox-group">
              <input type="checkbox" id="promotional-emails" x-model="formData.promotional_emails">
              <label for="promotional-emails">I'd like to receive promotional emails</label>
            </div>

            <p x-show="generalError" x-text="generalError" class="error-message"></p>

            <button type="submit" class="submit-btn" :disabled="isLoading">
              <span x-show="!isLoading">Sign Up</span>
              <span x-show="isLoading">Processing...</span>
            </button>
          </form>

          <div class="divider">
            <hr>
            <span>OR</span>
            <hr>
          </div>

          <div class="social-login">
            <a   href="{{ route('social.redirect', 'google') }}"  class="social-btn">
              <img src="../assets/images/75_13534.svg" alt="Google Icon">
              <span>Sign in with Google</span>
            </a>
            <a href="{{ route('social.redirect', 'facebook') }}"  class="social-btn">
              <img src="../assets/images/75_13546.svg" alt="Facebook Icon">
              <span>Sign in with Facebook</span>
            </a>
            {{-- <button class="social-btn">
              <img src="../assets/images/a2be846863522e8be393a7ed3a097e051ec9bbfb.png" alt="Apple Icon">
              <span>Sign in with Apple</span>
            </button> --}}
          </div>

          <p class="signin-link">
            Already have an account? <a href="#">Sign In</a>
          </p>
        </div>
      </main>
    </div>

    <!-- OTP Modal -->
    <div x-show="isModalOpen" class="modal-overlay">
      <div class="modal-content">
        <button @click="closeModal" class="modal-close">&times;</button>
        <h2 class="modal-title">Verify OTP</h2>
        <p class="modal-subtitle">
          OTP sent to <span class="font-semibold" x-text="formData.email"></span>
        </p>

        <input id="otp-input" type="text" maxlength="4" x-model="otpValue"
               @input="handleOtpInput"
               class="otp-input"
               :class="{ 'border-red-300': otpError, 'bg-gray-100': isVerifying }"
               placeholder="Enter OTP" autocomplete="one-time-code" />

        <p x-show="otpError" x-text="otpError" class="error-message"></p>
        <p x-show="otpSuccess" x-text="otpSuccess" class="success-message"></p>

        <div class="modal-actions">
          <button @click="handleVerifyOtp" :disabled="isVerifying || !isOtpComplete"
                  class="modal-btn modal-btn-primary">
            <span x-show="!isVerifying">Verify</span>
            <span x-show="isVerifying">Verifying...</span>
          </button>

          <button @click="resendCode" :disabled="isResending"
                  class="modal-btn modal-btn-secondary">
            <span x-show="!isResending">Resend Code</span>
            <span x-show="isResending">Sending...</span>
          </button>

          <button @click="closeModal" class="modal-btn modal-btn-danger">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </section>

  <script>
    function registerForm() {
      return {
        isModalOpen: false,
        isLoading: false,
        isVerifying: false,
        isResending: false,
        formData: {
          name: '',
          email: '',
          password: '',
          password_confirmation: '',
          referrer_code: '',
          promotional_emails: false,
          terms: false
        },
        otpValue: '',
        errors: {},
        generalError: '',
        otpError: '',
        otpSuccess: '',

        get isOtpComplete() {
          return this.otpValue.length === 4;
        },

        togglePasswordVisibility(fieldId) {
          const input = document.getElementById(fieldId);
          if (input.type === 'password') {
            input.type = 'text';
          } else {
            input.type = 'password';
          }
        },

        handleOtpInput(event) {
          let value = event.target.value.replace(/\D/g, '');
          if (value.length > 4) value = value.substring(0, 4);
          this.otpValue = value;

          if (value.length === 4 && !this.isVerifying) {
            setTimeout(() => this.handleVerifyOtp(), 300);
          }
          if (value && this.otpError) this.otpError = '';
        },

        async handleRegister() {
          this.isLoading = true;
          this.errors = {};
          this.generalError = '';

          try {
            const payload = {...this.formData};
            payload.promotional_emails = payload.promotional_emails ? 1 : 0;
            payload._token = '{{ csrf_token() }}';

            const response = await fetch('{{ route("register") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify(payload)
            });
            const data = await response.json();

            if (response.ok && data.success) {
              this.isModalOpen = true;
              this.otpValue = '';
              this.otpError = '';
              this.otpSuccess = '';
            } else {
              if (data.errors) this.errors = data.errors;
              else this.generalError = data.message || 'Registration failed. Please try again.';
            }
          } catch (error) {
            console.error(error);
            this.generalError = 'Something went wrong. Please try again.';
          } finally {
            this.isLoading = false;
          }
        },

        async handleVerifyOtp() {
          if (!this.isOtpComplete) { 
            this.otpError = 'Enter 4-digit OTP'; 
            return; 
          }
          
          this.isVerifying = true;
          this.otpError = '';
          this.otpSuccess = '';

          try {
            const payload = {...this.formData, otp: this.otpValue};
            payload.promotional_emails = payload.promotional_emails ? 1 : 0;
            payload._token = '{{ csrf_token() }}';

            const response = await fetch('{{ route("otp.verify") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify(payload),
              credentials: 'include'
            });

            const data = await response.json();
            if (data.success) {
              this.otpSuccess = 'Registration successful! Redirecting...';
              setTimeout(() => window.location.href = data.redirect || '{{ route("dashboard.index") }}', 1000);
            } else {
              this.otpError = data.message || 'Invalid OTP. Please try again.';
              this.otpValue = '';
            }
          } catch (error) {
            console.error(error);
            this.otpError = 'Verification failed. Please try again.';
            this.otpValue = '';
          } finally {
            this.isVerifying = false;
          }
        },

        async resendCode() {
          this.isResending = true;
          this.otpError = '';
          this.otpSuccess = '';
          
          try {
            const response = await fetch('{{ route("otp.resend") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({ email: this.formData.email, _token: '{{ csrf_token() }}' })
            });
            const data = await response.json();
            if (data.success) this.otpSuccess = data.message || 'New OTP sent!';
            else this.otpError = data.message || 'Failed to resend OTP. Please try again.';
          } catch (error) {
            console.error(error);
            this.otpError = 'Failed to resend OTP. Please try again.';
          } finally {
            this.isResending = false;
          }
        },

        closeModal() {
          this.isModalOpen = false;
          this.otpValue = '';
          this.otpError = '';
          this.otpSuccess = '';
        }
      }
    }
     // Function to redirect to home page
        function redirectToHome() {
            window.location.href = '{{ route("home") }}';
        }
  </script>
</body>
</html>