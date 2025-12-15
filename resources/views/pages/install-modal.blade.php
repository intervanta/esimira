{{-- INSTALL MODAL --}}
<div id="install-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="bg-[#F7EFE7] w-full max-w-lg rounded-3xl shadow-xl max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-lg">{{ __('Installation methods') }}</h3>
            <button onclick="closeInstallModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto px-6 pb-6">
            <div class="space-y-6 py-2">
                <!-- QR INSTALL -->
                <div class="bg-white rounded-2xl p-5 border border-gray-200">
                    <h4 class="font-medium mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-qrcode text-orange-500"></i>
                        {{ __('QR code installation') }}
                    </h4>
                    <p class="text-gray-600 text-sm mb-4">
                        {{ __('Please scan the QR code on the screen with your smartphone') }}
                    </p>

                    <div class="bg-[#F7EFE7] rounded-xl p-4 flex justify-center mb-4">
                        <div id="qr-container" class="relative">
                            <img id="install-qr"
                                 class="w-44 h-44 object-contain"
                                 alt="eSIM QR Code">
                            <div id="qr-loading" class="absolute inset-0 flex items-center justify-center bg-white/80 hidden">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
                            </div>
                        </div>
                    </div>

                    <button onclick="shareESIM()"
                            class="w-full border border-gray-300 hover:bg-gray-50 rounded-full py-2 text-sm font-medium transition">
                        <i class="fa-solid fa-share mr-2"></i>{{ __('Share eSIM') }}
                    </button>
                </div>

                <!-- OTHER INSTALLATION METHODS -->
                <div class="bg-white rounded-2xl p-5 border border-gray-200">
                    <h4 class="font-medium mb-4">
                        {{ __('Other installation methods') }}
                    </h4>

                    <!-- iOS -->
                    <div class="mb-4 rounded-xl bg-gray-50 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-brands fa-apple text-lg text-gray-800"></i>
                            <span class="font-medium text-gray-900">iOS</span>
                            <span class="ml-auto text-xs text-gray-400">iOS 17.4+</span>
                        </div>

                        <a id="install-ios-link"
                           href="#"
                           target="_blank"
                           class="text-sm text-orange-600 font-medium hover:underline block mb-3 truncate">
                            {{ __('Direct eSIM Installation Link') }}
                        </a>

                        <div class="text-sm space-y-2">
                            <div class="flex justify-between gap-3">
                                <span class="text-gray-500">{{ __('Activation Code') }}</span>
                                <span id="install-code" class="font-mono text-right break-all text-gray-900 max-w-[60%]"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-500">SM-DP+</span>
                                <span id="install-smdp" class="font-mono text-right break-all text-gray-900 max-w-[60%]"></span>
                            </div>
                        </div>

                        <div class="mt-3 flex gap-2">
                            <button onclick="copyCode()" 
                                    class="flex-1 border border-gray-300 hover:bg-gray-100 text-gray-700 py-2 px-3 rounded-lg text-xs font-medium">
                                <i class="fa-regular fa-copy mr-1"></i>{{ __('Copy Code') }}
                            </button>
                            <button onclick="copySMDP()" 
                                    class="flex-1 border border-gray-300 hover:bg-gray-100 text-gray-700 py-2 px-3 rounded-lg text-xs font-medium">
                                <i class="fa-regular fa-copy mr-1"></i>{{ __('Copy SM-DP+') }}
                            </button>
                        </div>
                    </div>

                    <!-- Android -->
                    <div class="rounded-xl bg-gray-50 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-brands fa-android text-lg text-green-600"></i>
                            <span class="font-medium text-gray-900">Android</span>
                        </div>

                        <div class="text-sm mb-3">
                            <span class="text-gray-500 block mb-1">{{ __('Activation Code') }}</span>
                            <span id="install-android-lpa"
                                  class="font-mono break-all text-gray-900 text-sm">
                            </span>
                        </div>

                        <button onclick="copyLPA()" 
                                class="w-full border border-gray-300 hover:bg-gray-100 text-gray-700 py-2 px-3 rounded-lg text-xs font-medium">
                            <i class="fa-regular fa-copy mr-1"></i>{{ __('Copy LPA Code') }}
                        </button>
                    </div>
                </div>

                <!-- NEED HELP SECTION -->
                <div class="bg-white rounded-2xl p-5 border border-gray-200">
                    <div class="flex items-center gap-3 mb-3">
                        <i class="fa-solid fa-circle-question text-orange-500 text-lg"></i>
                        <h4 class="font-medium">{{ __('Need help installing?') }}</h4>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4">
                        {{ __('Visit our comprehensive setup guide for step-by-step instructions.') }}
                    </p>
                    

                    
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs text-yellow-800 flex items-start gap-2">
                            <i class="fa-solid fa-lightbulb mt-0.5 text-yellow-500"></i>
                            <span>{{ __('Tip: Keep this page open until eSIM is successfully installed.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- HIDDEN DATA TEMPLATE (for dynamic activation data) --}}
<template id="activation-data-template">
    <script type="application/json" class="activation-data">
        {!! json_encode([
            'qr' => ':qr',
            'smdp' => ':smdp',
            'code' => ':code',
            'iccid' => ':iccid',
            'lpa' => ':lpa',
            'direct_link' => ':direct_link',
            'country' => ':country'
        ]) !!}
    </script>
</template>

{{-- INSTALL MODAL JAVASCRIPT --}}
<script>
// Global variable to store current activation data
let currentActivationData = {};

function openInstallModal(id) {
    const el = document.getElementById('activation-data-' + id);
    if (!el) return;

    currentActivationData = JSON.parse(el.textContent);
    
    // Show loading for QR
    document.getElementById('qr-loading').classList.remove('hidden');
    
    // Load QR code
    const qrImg = document.getElementById('install-qr');
    qrImg.onload = () => {
        document.getElementById('qr-loading').classList.add('hidden');
    };
    qrImg.onerror = () => {
        document.getElementById('qr-loading').classList.add('hidden');
        qrImg.src = 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="100%" height="100%" fill="%23f0f0f0"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="%23999" font-family="Arial" font-size="14">QR Code Not Available</text></svg>';
    };
    
    // Set QR code source
    const qrUrl = currentActivationData.qr ? ("{{ asset('storage/') }}" + '/' + currentActivationData.qr) : '';
    qrImg.src = qrUrl;
    
    // Set iOS direct link
    const iosLink = document.getElementById('install-ios-link');
    iosLink.href = currentActivationData.direct_link || '#';
    iosLink.title = currentActivationData.direct_link || 'No direct link available';
    
    // Set text content with fallbacks
    document.getElementById('install-code').textContent = currentActivationData.code || '-';
    document.getElementById('install-smdp').textContent = currentActivationData.smdp || '-';
    document.getElementById('install-android-lpa').textContent = currentActivationData.lpa || '-';
    
    // Truncate long text if needed
    truncateLongText('install-code', 20);
    truncateLongText('install-smdp', 25);
    truncateLongText('install-android-lpa', 30);

    // Show modal
    const modal = document.getElementById('install-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

// Alternative function to open modal with direct data (for use in details page)
function openInstallModalWithData(data) {
    currentActivationData = data;
    
    // Show loading for QR
    document.getElementById('qr-loading').classList.remove('hidden');
    
    // Load QR code
    const qrImg = document.getElementById('install-qr');
    qrImg.onload = () => {
        document.getElementById('qr-loading').classList.add('hidden');
    };
    qrImg.onerror = () => {
        document.getElementById('qr-loading').classList.add('hidden');
        qrImg.src = 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="100%" height="100%" fill="%23f0f0f0"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="%23999" font-family="Arial" font-size="14">QR Code Not Available</text></svg>';
    };
    
    // Set QR code source
    const qrUrl = currentActivationData.qr ? ("{{ asset('storage/') }}" + '/' + currentActivationData.qr) : '';
    qrImg.src = qrUrl;
    
    // Set iOS direct link
    const iosLink = document.getElementById('install-ios-link');
    iosLink.href = currentActivationData.direct_link || '#';
    iosLink.title = currentActivationData.direct_link || 'No direct link available';
    
    // Set text content with fallbacks
    document.getElementById('install-code').textContent = currentActivationData.code || '-';
    document.getElementById('install-smdp').textContent = currentActivationData.smdp || '-';
    document.getElementById('install-android-lpa').textContent = currentActivationData.lpa || '-';
    
    // Truncate long text if needed
    truncateLongText('install-code', 20);
    truncateLongText('install-smdp', 25);
    truncateLongText('install-android-lpa', 30);

    // Show modal
    const modal = document.getElementById('install-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeInstallModal() {
    const modal = document.getElementById('install-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    currentActivationData = {};
}

// Helper function to truncate text
function truncateLongText(elementId, maxLength) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const text = element.textContent;
    if (text.length > maxLength && text !== '-') {
        element.textContent = text.substring(0, maxLength) + '...';
        element.title = text; // Show full text on hover
    }
}

// Copy functions with toast notifications
async function copyToClipboard(text, buttonId = null) {
    if (!text || text === '-') {
        showToast('{{ __("Nothing to copy") }}', 'warning');
        return;
    }
    
    try {
        await navigator.clipboard.writeText(text);
        showToast('{{ __("Copied to clipboard") }}', 'success');
        
        if (buttonId) {
            const btn = document.getElementById(buttonId);
            if (btn) {
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check mr-1"></i>{{ __("Copied") }}';
                btn.classList.add('bg-green-100', 'text-green-700', 'border-green-300');
                
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.classList.remove('bg-green-100', 'text-green-700', 'border-green-300');
                }, 2000);
            }
        }
    } catch (err) {
        console.error('Copy failed:', err);
        showToast('{{ __("Failed to copy") }}', 'error');
    }
}

function copyCode() {
    const text = document.getElementById('install-code').textContent;
    copyToClipboard(text, 'copy-code-btn');
}

function copySMDP() {
    const text = document.getElementById('install-smdp').textContent;
    copyToClipboard(text, 'copy-smdp-btn');
}

function copyLPA() {
    const text = document.getElementById('install-android-lpa').textContent;
    copyToClipboard(text, 'copy-lpa-btn');
}

async function shareESIM() {
    const qrImg = document.getElementById('install-qr');
    const country = currentActivationData.country || 'Unknown Country';
    const code = document.getElementById('install-code').textContent;
    
    if (navigator.share) {
        try {
            await navigator.share({
                title: `eSIM Installation - ${country}`,
                text: `eSIM for ${country}\nActivation Code: ${code}\nScan QR code or use manual details.`,
                url: window.location.href,
            });
        } catch (err) {
            if (err.name !== 'AbortError') {
                // Fallback to QR code download
                downloadQRCode();
            }
        }
    } else {
        downloadQRCode();
    }
}

function downloadQRCode() {
    const qrImg = document.getElementById('install-qr');
    const link = document.createElement('a');
    link.download = `esim-qr-${currentActivationData.code || 'code'}.png`;
    link.href = qrImg.src;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showToast('{{ __("QR code downloaded") }}', 'info');
}

// Toast notification system
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `custom-toast fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white font-medium z-[100] transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        type === 'warning' ? 'bg-yellow-500' : 
        'bg-blue-500'
    }`;
    toast.textContent = message;
    toast.style.transform = 'translateX(100%)';
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// Close modal when clicking outside or pressing Escape
document.getElementById('install-modal').addEventListener('click', function(e) {
    if (e.target.id === 'install-modal') {
        closeInstallModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeInstallModal();
    }
});

// Initialize copy buttons in the DOM
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to iOS direct link for copying
    const iosLink = document.getElementById('install-ios-link');
    if (iosLink) {
        iosLink.addEventListener('click', function(e) {
            if (this.href === '#' || !currentActivationData.direct_link) {
                e.preventDefault();
                showToast('{{ __("No direct link available") }}', 'warning');
            }
        });
    }
});
</script>

<style>
#install-modal {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Scrollable content area */
.max-h-\[90vh\] {
    max-height: 90vh;
}

.flex-1 {
    flex: 1 1 0%;
}

.overflow-y-auto {
    overflow-y: auto;
}

/* Custom scrollbar styling */
#install-modal div.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

#install-modal div.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
    margin: 4px;
}

#install-modal div.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#install-modal div.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Toast animation */
.custom-toast {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Truncate text with ellipsis */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* QR code loading animation */
#qr-loading {
    backdrop-filter: blur(2px);
}
</style>