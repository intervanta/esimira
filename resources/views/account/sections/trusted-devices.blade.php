<div id="trusted-devices" class="hidden">
    <div class="content-header mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Trusted Devices</h3>
    </div>

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
        <p class="text-gray-800 mb-2">
            <b>Share amazing eSIM technology securely</b>
        </p>
        <p class="text-gray-700 text-sm">
            The devices listed below are trusted by your account, including web browsers.
            <a href="#" class="font-semibold underline">Learn more</a>. Tap to find out more about trusted devices linked to your Esimira account.
            If you notice any unfamiliar device, consider 
            <a href="" class="font-semibold underline">changing your password</a> 
            to protect your account.
        </p>
    </div>

    <!-- Loading State -->
    <div id="trusted-devices-loading" class="text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-600"></div>
        <p class="text-gray-500 mt-2">Loading trusted devices...</p>
    </div>

    <!-- Error State -->
    <div id="trusted-devices-error" class="hidden text-center py-8">
        <p class="text-red-600">Failed to load trusted devices. Please try again.</p>
        <button onclick="loadTrustedDevices()" class="mt-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition duration-200">
            Retry
        </button>
    </div>

    <!-- Devices List -->
    <div class="space-y-4 hidden" id="trusted-devices-list">
        <!-- Devices will be loaded here via JavaScript -->
    </div>

    <div class="mt-6">
        <button 
            onclick="loadTrustedDevices()" 
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200"
        >
            Refresh List
        </button>
    </div>
</div>

<!-- Remove Confirmation Popup -->
<div id="remove-confirmation-popup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Remove Device</h3>
        </div>
        
        <p class="text-gray-600 mb-6">Are you sure you want to remove this trusted device? You'll need to log in again from this device.</p>
        
        <div class="flex justify-end space-x-3">
            <button 
                onclick="hideRemovePopup()" 
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200"
            >
                Cancel
            </button>
            <button 
                id="confirm-remove-btn"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200"
            >
                Remove Device
            </button>
        </div>
    </div>
</div>

<script>
// Debug logging
console.log('Trusted Devices script loaded');

let currentDeviceToRemove = null;

// Safe CSRF token getter
function getCsrfToken() {
    try {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        const token = metaTag ? metaTag.getAttribute('content') : null;
        console.log('CSRF Token found:', token ? 'Yes' : 'No');
        return token;
    } catch (error) {
        console.error('Error getting CSRF token:', error);
        return null;
    }
}

// Safe element getter with error handling
function getElementSafe(id) {
    const element = document.getElementById(id);
    if (!element) {
        console.warn(`Element with id '${id}' not found`);
    }
    return element;
}

// Popup functions
function showRemovePopup(deviceId) {
    currentDeviceToRemove = deviceId;
    const popup = getElementSafe('remove-confirmation-popup');
    if (popup) {
        popup.classList.remove('hidden');
    }
}

function hideRemovePopup() {
    currentDeviceToRemove = null;
    const popup = getElementSafe('remove-confirmation-popup');
    if (popup) {
        popup.classList.add('hidden');
    }
}

// Add this to your scripts section
async function loadTrustedDevices() {
    console.log('Loading trusted devices...');
    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    try {
        // Show loading state safely
        const loadingEl = getElementSafe('trusted-devices-loading');
        const errorEl = getElementSafe('trusted-devices-error');
        const listEl = getElementSafe('trusted-devices-list');
        
        if (loadingEl) loadingEl.classList.remove('hidden');
        if (errorEl) errorEl.classList.add('hidden');
        if (listEl) listEl.classList.add('hidden');
        
        console.log('Making API request to trusted devices endpoint...');
        const response = await fetch('{{ route("trusted-devices.index") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Devices data received:', data);
        
        // Hide loading state safely
        if (loadingEl) loadingEl.classList.add('hidden');
        
        if (data.devices && data.devices.length > 0) {
            displayTrustedDevices(data.devices, data.current_device_id);
        } else {
            displayNoDevices();
        }
        
    } catch (error) {
        console.error('Failed to load trusted devices:', error);
        const loadingEl = getElementSafe('trusted-devices-loading');
        const errorEl = getElementSafe('trusted-devices-error');
        if (loadingEl) loadingEl.classList.add('hidden');
        if (errorEl) errorEl.classList.remove('hidden');
    }
}

function displayTrustedDevices(devices, currentDeviceId) {
    const container = getElementSafe('trusted-devices-list');
    
    if (!container) return;
    
    console.log('Displaying devices, current device ID:', currentDeviceId);
    
    container.innerHTML = devices.map(device => {
        console.log('Processing device:', device.device_id, 'Current:', device.device_id === currentDeviceId);
        return `
        <div class="device-tile flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center space-x-4">
                <div class="device-icon flex items-center justify-center w-12 h-12 rounded-lg bg-gray-100 text-gray-600">
                    ${getDeviceIcon(device.device_type)}
                </div>
                <div class="device-info">
                    <div class="device-title font-semibold text-gray-800 flex items-center">
                        ${escapeHtml(device.browser)} on ${escapeHtml(device.platform)}
                        ${device.device_id === currentDeviceId ? 
                            '<span class="current-badge ml-2">Current Device</span>' : ''}
                    </div>
                    <div class="device-details text-sm text-gray-600 mt-1">
                        <div class="flex flex-wrap gap-x-4 gap-y-1">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Last login: ${formatDate(device.last_login_at)}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                ${escapeHtml(device.location || 'Unknown')}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Expires: ${formatDate(device.expires_at)}
                            </span>
                        </div>
                        ${device.is_expired ? '<div class="text-red-600 text-xs mt-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Expired</div>' : ''}
                    </div>
                </div>
            </div>
            <div>
                ${device.device_id !== currentDeviceId ? 
                    `<button onclick="showRemovePopup('${escapeHtml(device.device_id)}')" 
                            class="remove-btn px-4 py-2 text-sm rounded-lg transition duration-200"
                            data-device-id="${escapeHtml(device.device_id)}">
                        Remove
                    </button>` : 
                    '<span class="current-indicator px-4 py-2 rounded-lg text-sm">Current</span>'
                }
            </div>
        </div>
    `}).join('');
    
    container.classList.remove('hidden');
}

function displayNoDevices() {
    const container = getElementSafe('trusted-devices-list');
    if (!container) return;
    
    container.innerHTML = `
        <div class="text-center py-8 text-gray-500 bg-white border border-gray-200 rounded-lg">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <p class="mb-2 text-lg font-medium">No trusted devices found</p>
            <p class="text-sm">Your trusted devices will appear here when you login from new devices.</p>
        </div>
    `;
    container.classList.remove('hidden');
}

function getDeviceIcon(deviceType) {
    const icons = {
        'mobile': `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
        `,
        'tablet': `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
        `,
        'desktop': `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        `
    };
    return icons[deviceType] || icons.desktop;
}

// Utility function to escape HTML
function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') return unsafe;
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Utility function to format dates
function formatDate(dateString) {
    try {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    } catch (error) {
        return 'Invalid Date';
    }
}

async function removeDevice(deviceId) {
    console.log('removeDevice called with ID:', deviceId);
    
    if (!deviceId) {
        console.error('No device ID provided');
        return;
    }

    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    try {
        console.log('Sending DELETE request for device:', deviceId);
        
        const route = `{{ route("trusted-devices.destroy", "") }}/${deviceId}`;
        console.log('DELETE route:', route);
        
        const response = await fetch(route, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        console.log('Delete response status:', response.status);
        
        const data = await response.json();
        console.log('Delete response data:', data);
        
        if (data.success) {
            hideRemovePopup();
            loadTrustedDevices(); // Reload the list
        }
    } catch (error) {
        console.error('Failed to remove device:', error);
    }
}

// Load devices when the section is shown
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Trusted Devices');
    
    // Setup confirm remove button
    const confirmRemoveBtn = getElementSafe('confirm-remove-btn');
    if (confirmRemoveBtn) {
        confirmRemoveBtn.addEventListener('click', function() {
            if (currentDeviceToRemove) {
                removeDevice(currentDeviceToRemove);
            }
        });
    }
    
    // Close popup when clicking outside
    const popup = getElementSafe('remove-confirmation-popup');
    if (popup) {
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                hideRemovePopup();
            }
        });
    }
    
    // Check if trusted devices section is active
    const trustedDevicesLink = document.querySelector('a[data-target="trusted-devices"]');
    if (trustedDevicesLink) {
        trustedDevicesLink.addEventListener('click', function() {
            console.log('Trusted devices link clicked');
            setTimeout(loadTrustedDevices, 100);
        });
    }
    
    // Load immediately if section is already visible
    const trustedDevicesSection = document.getElementById('trusted-devices');
    if (trustedDevicesSection && !trustedDevicesSection.classList.contains('hidden')) {
        console.log('Trusted devices section visible, loading devices...');
        // Wait a bit for the DOM to be fully ready
        setTimeout(loadTrustedDevices, 500);
    }
});
</script>

<style>
/* Additional styles for the redesigned trusted devices */
.device-tile {
    transition: all 0.2s ease;
}

.device-tile:hover {
    transform: translateY(-2px);
}

.current-badge {
    background-color: #f3f4f6;
    color: rgb(70 170 196/var(--tw-border-opacity));
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid #e5e7eb;
}

.current-indicator {
    background-color: #f3f4f6;
    color: rgb(70 170 196/var(--tw-border-opacity));
    border: 1px solid #e5e7eb;
    font-weight: 500;
}

.remove-btn {
    background-color: #f3f4f6;
    color: #374151;
    border: 1px solid #e5e7eb;
    font-weight: 500;
    cursor: pointer;
}

.remove-btn:hover {
    background-color: #e5e7eb;
}

.device-icon {
    transition: all 0.2s ease;
}

.device-tile:hover .device-icon {
    transform: scale(1.05);
}

/* Popup animations */
#remove-confirmation-popup {
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>