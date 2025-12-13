<div id="account-info" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">

    <!-- Title -->
    <h2 class="text-xl font-semibold text-gray-900 mb-6">
        Account Information
    </h2>

    <!-- Form -->
    <form id="profile-form" class="profile-form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- First Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ auth()->user()->name }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl
                              focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       required>
                <p class="error-msg text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <!-- Last Name (Optional) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name (Optional)</label>
                <input type="text"
                       name="last_name"
                       id="last_name"
                       value="{{ auth()->user()->last_name ?? '' }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl
                              focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <p class="error-msg text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <!-- Contact Number with Country Code -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                <div class="flex gap-3">
                    <!-- Country Code -->
                    <div class="w-24">
                        <input type="text"
                               name="country_code"
                               id="country_code"
                               value="{{ auth()->user()->country_code ?? '+1' }}"
                               placeholder="+1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                      focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <p class="error-msg text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    
                    <!-- Contact Number -->
                    <div class="flex-1">
                        <input type="text"
                               name="contact_number"
                               id="contact_number"
                               value="{{ auth()->user()->contact_number ?? '' }}"
                               placeholder="(555) 123-4567"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                      focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <p class="error-msg text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>
            </div>

            <!-- Email (Locked) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <div class="relative">
                    <input type="email"
                           id="email"
                           value="{{ auth()->user()->email }}"
                           readonly disabled
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50
                                  cursor-not-allowed pr-12">
                    <span class="absolute right-3 top-3.5 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="pt-6 flex flex-col md:flex-row gap-4">
            <button type="submit"
                    class="px-8 py-3 bg-orange-500 text-white font-medium rounded-xl
                           hover:bg-orange-600 transition">
                Save Changes
            </button>
        </div>
    </form>

    <!-- Password Change Link -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-700">Password</h3>
                <p id="password-last-changed" class="text-sm text-gray-500 mt-1">
                    @if(auth()->user()->password_changed_at)
                        Last changed {{ auth()->user()->password_changed_at->diffForHumans() }}
                    @else
                        Password never changed
                    @endif
                </p>
            </div>
            <button id="change-password-btn" 
                    class="text-orange-500 hover:text-orange-600 font-medium flex items-center gap-2 transition">
                <i class="fas fa-key"></i>
                Change Password
            </button>
        </div>
    </div>

    <!-- Notification Preferences -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <span class="text-gray-700">I'd like to receive promotional emails.</span>
            <!-- Toggle -->
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="promotional_emails" class="sr-only peer" 
                    {{ auth()->user()->promotional_emails ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full
                            peer-checked:bg-orange-500 transition"></div>
                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition
                            peer-checked:translate-x-5"></div>
            </label>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="password-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Change Password</h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="password-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" 
                           name="current_password"
                           id="current-password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           required>
                    <p class="error-msg text-red-500 text-xs mt-1 hidden"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" 
                           name="new_password"
                           id="new-password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           required>
                    <p class="error-msg text-red-500 text-xs mt-1 hidden"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" 
                           name="new_password_confirmation"
                           id="confirm-password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           required>
                    <p class="error-msg text-red-500 text-xs mt-1 hidden"></p>
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" id="cancel-password" class="px-4 py-2 text-gray-700 font-medium rounded-xl
                           hover:bg-gray-100 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-orange-500 text-white font-medium rounded-xl
                           hover:bg-orange-600 transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Global Notification Display -->
    <div id="globalNotification" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg border border-gray-200 p-4 z-40 hidden">
        <div class="flex items-center gap-3">
            <div id="notificationIcon" class="w-8 h-8 rounded-full flex items-center justify-center">
                <!-- Icon will be dynamically set -->
            </div>
            <div>
                <p id="notificationTitle" class="text-sm font-medium text-gray-900"></p>
                <p id="notificationMessage" class="text-xs text-gray-500"></p>
            </div>
        </div>
    </div>

    <style>
    /* Custom styles to match the design */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9fafb;
        padding: 20px;
    }

    #account-info {
        max-width: 800px;
        margin: 0 auto;
    }

    .error-msg {
        transition: all 0.3s ease;
    }

    /* Notification Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideOutDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }

    .notification-show {
        animation: slideInUp 0.3s ease-out forwards;
    }

    .notification-hide {
        animation: slideOutDown 0.3s ease-in forwards;
    }

    /* Modal animation */
    #password-modal {
        transition: opacity 0.3s ease;
    }

    #password-modal > div {
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    #password-modal:not(.hidden) > div {
        transform: scale(1);
    }

    /* Loading state */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Success and Error Colors */
    .notification-success {
        background-color: #10B981;
    }

    .notification-error {
        background-color: #EF4444;
    }

    .notification-warning {
        background-color: #F59E0B;
    }

    .notification-info {
        background-color: #3B82F6;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Profile form submission
        const profileForm = document.getElementById('profile-form');
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearErrors();
            
            // Validate form
            if (validateProfileForm()) {
                updateProfile(this);
            }
        });

        // Promo emails toggle
        const promoEmailsToggle = document.getElementById('promotional_emails');
        promoEmailsToggle.addEventListener('change', function() {
            updatePromoEmails(this.checked);
        });

        // Password change modal
        const changePasswordBtn = document.getElementById('change-password-btn');
        const passwordModal = document.getElementById('password-modal');
        const closeModal = document.getElementById('close-modal');
        const cancelPassword = document.getElementById('cancel-password');
        
        changePasswordBtn.addEventListener('click', function() {
            passwordModal.classList.remove('hidden');
        });
        
        closeModal.addEventListener('click', function() {
            passwordModal.classList.add('hidden');
            clearPasswordForm();
        });
        
        cancelPassword.addEventListener('click', function() {
            passwordModal.classList.add('hidden');
            clearPasswordForm();
        });
        
        // Password form submission
        const passwordForm = document.getElementById('password-form');
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearPasswordErrors();
            
            // Validate form
            if (validatePasswordForm()) {
                updatePassword(this);
            }
        });
        
        // Form validation functions
        function validateProfileForm() {
            let isValid = true;
            const name = document.getElementById('name');
            
            if (!name.value.trim()) {
                showFieldError(name, 'First name is required');
                isValid = false;
            }
            
            return isValid;
        }
        
        function validatePasswordForm() {
            let isValid = true;
            const currentPassword = document.getElementById('current-password');
            const newPassword = document.getElementById('new-password');
            const confirmPassword = document.getElementById('confirm-password');
            
            if (!currentPassword.value.trim()) {
                showFieldError(currentPassword, 'Current password is required');
                isValid = false;
            }
            
            if (!newPassword.value.trim()) {
                showFieldError(newPassword, 'New password is required');
                isValid = false;
            } else if (newPassword.value.length < 8) {
                showFieldError(newPassword, 'Password must be at least 8 characters');
                isValid = false;
            }
            
            if (!confirmPassword.value.trim()) {
                showFieldError(confirmPassword, 'Please confirm your password');
                isValid = false;
            } else if (newPassword.value !== confirmPassword.value) {
                showFieldError(confirmPassword, 'Passwords do not match');
                isValid = false;
            }
            
            return isValid;
        }
        
        // Helper functions
        function showFieldError(field, message) {
            const errorElement = field.nextElementSibling;
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
        }
        
        function clearErrors() {
            const errorElements = document.querySelectorAll('.error-msg');
            errorElements.forEach(el => {
                el.classList.add('hidden');
            });
            
            const fields = document.querySelectorAll('input');
            fields.forEach(field => {
                field.classList.remove('border-red-500');
            });
        }
        
        function clearPasswordErrors() {
            const errorElements = document.querySelectorAll('#password-form .error-msg');
            errorElements.forEach(el => {
                el.classList.add('hidden');
            });
            
            const fields = document.querySelectorAll('#password-form input');
            fields.forEach(field => {
                field.classList.remove('border-red-500');
            });
        }
        
        function clearPasswordForm() {
            document.getElementById('password-form').reset();
            clearPasswordErrors();
        }

        // Notification System
        function showNotification(type, title, message, duration = 5000) {
            const notification = document.getElementById('globalNotification');
            const icon = document.getElementById('notificationIcon');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            
            // Set notification type and content
            const notificationConfig = {
                success: {
                    icon: 'check',
                    color: 'notification-success',
                    iconSvg: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
                },
                error: {
                    icon: 'exclamation',
                    color: 'notification-error',
                    iconSvg: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                },
                warning: {
                    icon: 'exclamation-triangle',
                    color: 'notification-warning',
                    iconSvg: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>'
                },
                info: {
                    icon: 'information-circle',
                    color: 'notification-info',
                    iconSvg: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                }
            };
            
            const config = notificationConfig[type] || notificationConfig.info;
            
            // Set notification content
            icon.className = `w-8 h-8 ${config.color} rounded-full flex items-center justify-center`;
            icon.innerHTML = config.iconSvg;
            notificationTitle.textContent = title;
            notificationMessage.textContent = message;
            
            // Show notification with animation
            notification.classList.remove('hidden', 'notification-hide');
            notification.classList.add('notification-show');
            
            // Auto hide after duration
            setTimeout(() => {
                hideNotification();
            }, duration);
        }
        
        function hideNotification() {
            const notification = document.getElementById('globalNotification');
            notification.classList.remove('notification-show');
            notification.classList.add('notification-hide');
            
            setTimeout(() => {
                notification.classList.add('hidden');
                notification.classList.remove('notification-hide');
            }, 300);
        }

        // API Functions
        async function updateProfile(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            try {
                // Show loading state
                submitBtn.innerHTML = '<div class="loading-spinner"></div> Saving...';
                submitBtn.disabled = true;
                
                const formData = new FormData(form);
                
                const response = await fetch('{{ route("customer.profile.update") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', 'Profile Updated', 'Your profile has been updated successfully');
                } else {
                    if (data.errors) {
                        // Display field errors
                        Object.keys(data.errors).forEach(field => {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                showFieldError(input, data.errors[field][0]);
                            }
                        });
                        showNotification('error', 'Update Failed', 'Please check the form for errors');
                    } else {
                        showNotification('error', 'Update Failed', data.message || 'Failed to update profile');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Network Error', 'Please check your connection and try again');
            } finally {
                // Restore button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        async function updatePromoEmails(value) {
            try {
                const response = await fetch('{{ route("customer.promo-emails.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        promotional_emails: value
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', 'Preferences Updated', 'Your notification preferences have been updated');
                } else {
                    showNotification('error', 'Update Failed', data.message || 'Failed to update preferences');
                    // Revert toggle
                    document.getElementById('promotional_emails').checked = !value;
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Network Error', 'Please check your connection and try again');
                // Revert toggle
                document.getElementById('promotional_emails').checked = !value;
            }
        }

        async function updatePassword(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            try {
                // Show loading state
                submitBtn.innerHTML = '<div class="loading-spinner"></div> Updating...';
                submitBtn.disabled = true;
                
                const formData = new FormData(form);
                
                const response = await fetch('{{ route("customer.password.update") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    passwordModal.classList.add('hidden');
                    clearPasswordForm();
                    showNotification('success', 'Password Updated', 'Your password has been changed successfully');
                    
                    // Update password last changed text
                    document.getElementById('password-last-changed').textContent = 'Last changed just now';
                } else {
                    if (data.errors) {
                        // Display field errors
                        Object.keys(data.errors).forEach(field => {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                showFieldError(input, data.errors[field][0]);
                            }
                        });
                        showNotification('error', 'Update Failed', 'Please check the form for errors');
                    } else {
                        showNotification('error', 'Update Failed', data.message || 'Failed to update password');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Network Error', 'Please check your connection and try again');
            } finally {
                // Restore button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }
    });
    </script>
</div>