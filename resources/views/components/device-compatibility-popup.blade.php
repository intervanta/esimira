<!-- Device Compatibility Popup -->
<div id="device-compatibility-popup" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-3 pt-3 pb-16 text-center sm:block sm:p-0">
        <!-- Background Overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

        <!-- Modal Panel -->
        <div class="relative inline-block w-full max-w-4xl px-4 pt-4 pb-3 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-6 sm:align-middle sm:max-w-4xl sm:w-full sm:p-5">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">
                        Device Compatibility
                    </h3>
                    <p class="mt-1 text-xs text-gray-500">
                        Check if your device supports eSIM
                    </p>
                </div>
                <button type="button" id="close-popup" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary">
                    <span class="sr-only">Close</span>
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Search and Filters -->
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <div class="flex flex-col lg:flex-row gap-3 justify-between items-start lg:items-center">
                    <!-- Search Box -->
                    <div class="flex-1 w-full lg:max-w-sm">
                        <div class="relative">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="popup-device-search" placeholder="Search devices..." 
                                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto items-center">
                        <!-- Filters -->
                        <select id="popup-device-type-filter" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent min-w-[140px]">
                            <option value="all">All Types</option>
                            @foreach($deviceTypes as $type)
                                <option value="{{ strtolower($type->name) }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Reset Button -->
                        <button id="popup-reset-filters" class="btn btn-outline btn-xs border-gray-300 text-gray-700 hover:bg-gray-50">
                            <i class="fa-solid fa-refresh mr-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex flex-wrap gap-3 mt-3 pt-3 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-gray-600 rounded-full"></span>
                        <span class="text-xs text-gray-600">
                            <span id="popup-compatible-count" class="font-semibold text-gray-900">
                                {{ $deviceTypes->flatMap->brands->flatMap->models->where('has_esim', true)->count() }}
                            </span> Compatible
                        </span>
                    </div>
                </div>
            </div>

            <!-- Device Cards Grid -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden max-h-[60vh] overflow-y-auto">
                <!-- Header -->
                <div class="bg-gray-50 px-3 py-2 border-b border-gray-200 sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <h4 class="text-base font-semibold text-gray-900">Compatible Devices</h4>
                        <span id="popup-showing-count" class="text-xs text-gray-600">
                            {{ $deviceTypes->flatMap->brands->flatMap->models->where('has_esim', true)->count() }} devices
                        </span>
                    </div>
                </div>

                <!-- Device Cards Container -->
                <div class="p-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="popup-device-cards-container">
                        @foreach($deviceTypes as $deviceType)
                            @foreach($deviceType->brands as $brand)
                                @foreach($brand->models->where('has_esim', true) as $model)
                                @php
                                    $deviceTypeIcons = [
                                        'smartphones' => 'fa-solid fa-mobile-screen',
                                        'other devices' => 'fa-solid fa-laptop',
                                        'default' => 'fa-solid fa-mobile'
                                    ];
                                    
                                    $deviceTypeName = strtolower($deviceType->name);
                                    $deviceIcon = $deviceTypeIcons[$deviceTypeName] ?? $deviceTypeIcons['default'];
                                @endphp
                                <div class="popup-device-card bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-all duration-200 hover:border-gray-300"
                                     data-device="{{ strtolower($model->name) }}"
                                     data-brand="{{ strtolower($brand->name) }}"
                                     data-type="{{ strtolower($deviceType->name) }}"
                                     data-compatible="true">
                                    <!-- Card Header -->
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                                <i class="fa-solid fa-check text-gray-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-semibold text-gray-900 truncate max-w-[120px]">{{ $model->name }}</h5>
                                                <p class="text-xs text-gray-500">{{ $brand->name }}</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            <i class="{{ $deviceIcon }} text-xs"></i>
                                        </span>
                                    </div>

                                    <!-- Compatibility Status -->
                                    <div class="mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            <i class="fa-solid fa-mobile-screen mr-1 text-xs"></i>
                                            eSIM Ready
                                        </span>
                                    </div>

                                    <!-- Exceptions -->
                                    @if($model->exceptions->count() > 0)
                                    <div class="mt-2">
                                        <button type="button" class="w-full text-left text-xs text-gray-600 hover:text-gray-700 font-medium flex items-center justify-between gap-1 exception-toggle">
                                            <span>View Notes ({{ $model->exceptions->count() }})</span>
                                            <i class="fa-solid fa-chevron-down text-xs transition-transform flex-shrink-0"></i>
                                        </button>
                                        <div class="exception-content hidden mt-2 p-2 bg-gray-50 border border-gray-200 rounded text-xs">
                                            <p class="font-semibold text-gray-800 mb-1">Important:</p>
                                            <ul class="text-gray-700 space-y-1">
                                                @foreach($model->exceptions as $exception)
                                                <li class="flex items-start gap-1">
                                                    <span class="text-gray-500 mt-0.5 flex-shrink-0">•</span>
                                                    <span class="leading-relaxed">{{ $exception->exception }}</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @else
                                    <div class="mt-2">
                                        <span class="text-xs text-gray-400">No special requirements</span>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <!-- Empty State -->
                <div id="popup-empty-state" class="hidden px-6 py-8 text-center">
                    <i class="fa-solid fa-search text-2xl text-gray-300 mb-2"></i>
                    <h4 class="text-sm font-medium text-gray-900 mb-1">No devices found</h4>
                    <p class="text-xs text-gray-600">Try adjusting your search</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Popup Configuration
    let popupAllDevices = Array.from(document.querySelectorAll('.popup-device-card'));
    let popupFilteredDevices = [...popupAllDevices];
    
    // Initialize Popup
    popupUpdateCounts();
    
    // Close popup functionality
    function closeDeviceCompatibilityPopup() {
        const popup = document.getElementById('device-compatibility-popup');
        if (popup) {
            popup.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
    
    // Close popup when clicking X button
    const closeButton = document.getElementById('close-popup');
    if (closeButton) {
        closeButton.addEventListener('click', closeDeviceCompatibilityPopup);
    }
    
    // Close popup when clicking outside
    const popup = document.getElementById('device-compatibility-popup');
    if (popup) {
        popup.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeviceCompatibilityPopup();
            }
        });
    }
    
    // Close popup with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeviceCompatibilityPopup();
        }
    });
    
    // Exception toggle functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.exception-toggle')) {
            const button = e.target.closest('.exception-toggle');
            const content = button.nextElementSibling;
            const icon = button.querySelector('.fa-chevron-down');
            
            // Close all other open exceptions
            document.querySelectorAll('.exception-content').forEach(item => {
                if (item !== content) {
                    item.classList.add('hidden');
                }
            });
            document.querySelectorAll('.exception-toggle .fa-chevron-down').forEach(item => {
                if (item !== icon) {
                    item.classList.remove('rotate-180');
                }
            });
            
            // Toggle current
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    });
    
    // Popup Search functionality
    const popupSearchInput = document.getElementById('popup-device-search');
    const popupTypeFilter = document.getElementById('popup-device-type-filter');
    
    popupSearchInput.addEventListener('input', popupPerformFilter);
    popupTypeFilter.addEventListener('change', popupPerformFilter);
    
    // Popup Reset filters
    document.getElementById('popup-reset-filters').addEventListener('click', function() {
        popupSearchInput.value = '';
        popupTypeFilter.value = 'all';
        popupPerformFilter();
        
        // Close all open exceptions
        document.querySelectorAll('.exception-content').forEach(item => {
            item.classList.add('hidden');
        });
        document.querySelectorAll('.exception-toggle .fa-chevron-down').forEach(item => {
            item.classList.remove('rotate-180');
        });
    });
    
    function popupPerformFilter() {
        const searchTerm = popupSearchInput.value.toLowerCase().trim();
        const selectedType = popupTypeFilter.value;
        
        popupFilteredDevices = popupAllDevices.filter(card => {
            const device = card.getAttribute('data-device');
            const brand = card.getAttribute('data-brand');
            const type = card.getAttribute('data-type');
            
            // Search filter
            const matchesSearch = searchTerm === '' || 
                                device.includes(searchTerm) || 
                                brand.includes(searchTerm) ||
                                type.includes(searchTerm);
            
            // Type filter
            const matchesType = selectedType === 'all' || type === selectedType;
            
            return matchesSearch && matchesType;
        });
        
        // Update UI
        popupUpdateCounts();
        popupRenderCards();
    }
    
    function popupRenderCards() {
        // Hide all cards
        popupAllDevices.forEach(card => card.style.display = 'none');
        
        // Show filtered cards
        popupFilteredDevices.forEach(card => card.style.display = 'block');
        
        // Show/hide empty state
        const emptyState = document.getElementById('popup-empty-state');
        const cardsContainer = document.getElementById('popup-device-cards-container');
        
        if (popupFilteredDevices.length === 0) {
            emptyState.classList.remove('hidden');
            cardsContainer.classList.add('hidden');
        } else {
            emptyState.classList.add('hidden');
            cardsContainer.classList.remove('hidden');
        }
    }
    
    function popupUpdateCounts() {
        const compatibleCount = popupFilteredDevices.length;
        
        document.getElementById('popup-compatible-count').textContent = compatibleCount;
        document.getElementById('popup-showing-count').textContent = `${compatibleCount} devices`;
    }
    
    // Initialize popup
    popupPerformFilter();
});

// Make close function globally available
window.closeDeviceCompatibilityPopup = function() {
    const popup = document.getElementById('device-compatibility-popup');
    if (popup) {
        popup.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};
</script>

<style>
.popup-device-card {
    transition: all 0.2s ease-in-out;
}

.exception-toggle {
    transition: all 0.2s ease-in-out;
}

.exception-content {
    animation: slideDown 0.2s ease-out;
}

.rotate-180 {
    transform: rotate(180deg);
}

/* Smooth animations for popup */
#device-compatibility-popup {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from { 
        opacity: 0;
        transform: translateY(-5px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

/* Scrollbar styling */
#device-compatibility-popup ::-webkit-scrollbar {
    width: 4px;
}

#device-compatibility-popup ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

#device-compatibility-popup ::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

#device-compatibility-popup ::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #device-compatibility-popup .max-w-4xl {
        margin: 0.25rem;
        padding: 0.5rem;
    }
    
    .popup-device-card {
        padding: 0.75rem;
    }
}

@media (max-width: 640px) {
    #popup-device-cards-container {
        grid-template-columns: 1fr;
    }
    
    .popup-device-card {
        font-size: 0.8rem;
    }
}

/* Card hover effects */
.popup-device-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Compact button styles */
.btn-xs {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 0.375rem;
}

.btn-outline.btn-xs {
    background: white;
    border: 1px solid #d1d5db;
    color: #374151;
    transition: all 0.2s ease-in-out;
}

.btn-outline.btn-xs:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}
</style>