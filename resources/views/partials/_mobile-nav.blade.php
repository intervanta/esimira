<!-- =========== Mobile Bottom Nav - With Search =========== -->
<nav class="fixed bottom-0 left-0 w-full bg-white shadow-[0_-2px_10px_#0000001a] flex justify-around items-center py-3 border-t border-gray-200 lg:hidden z-50 font-[Satoshi]">
    @php
        $mobileNav = [
            ['route' => 'home', 'label' => 'Home', 'default_icon' => 'home.png', 'active_icon' => 'home_active.png'],
            ['route' => 'about', 'label' => 'About', 'default_icon' => 'about.png', 'active_icon' => 'about_active.png'],
            ['route' => 'plans', 'label' => 'Our Plan', 'default_icon' => 'ourplan.png', 'active_icon' => 'planactive.png'],
            ['route' => 'search', 'label' => 'Search', 'default_icon' => 'search.svg', 'active_icon' => 'search.svg', 'type' => 'search'],
        ];
        
        $currentRoute = request()->route()->getName();
    @endphp

    @foreach ($mobileNav as $item)
        @php
            $isActive = $currentRoute === $item['route'];
            $isSearch = isset($item['type']) && $item['type'] === 'search';
        @endphp
        
        @if($isSearch)
            <!-- Search Button -->
            <button type="button" 
                   id="mobileSearchTrigger"
                   class="flex flex-col items-center {{ $isActive ? 'text-[#f4633a]' : 'text-[#777]' }}">
                <!-- Default Icon -->
                <img src="{{ asset('assets/images/' . $item['default_icon']) }}" 
                     alt="{{ $item['label'] }}" 
                     class="w-5 h-5 mb-1 {{ $isActive ? 'hidden' : 'block' }}">
                
                <!-- Active Icon -->
                <img src="{{ asset('assets/images/' . $item['active_icon']) }}" 
                     alt="{{ $item['label'] }} Active" 
                     class="w-5 h-5 mb-1 {{ $isActive ? 'block' : 'hidden' }}">
                
                <span class="text-[14px] font-[400] leading-[100%] tracking-[0px]">{{ $item['label'] }}</span>
            </button>
        @else
            <!-- Regular Navigation Link -->
            <a href="{{ route($item['route']) }}" 
               class="flex flex-col items-center {{ $isActive ? 'text-[#f4633a]' : 'text-[#777]' }}">
                <!-- Default Icon -->
                <img src="{{ asset('assets/images/' . $item['default_icon']) }}" 
                     alt="{{ $item['label'] }}" 
                     class="w-5 h-5 mb-1 {{ $isActive ? 'hidden' : 'block' }}">
                
                <!-- Active Icon -->
                <img src="{{ asset('assets/images/' . $item['active_icon']) }}" 
                     alt="{{ $item['label'] }} Active" 
                     class="w-5 h-5 mb-1 {{ $isActive ? 'block' : 'hidden' }}">
                
                <span class="text-[14px] font-[400] leading-[100%] tracking-[0px]">{{ $item['label'] }}</span>
            </a>
        @endif
    @endforeach
</nav>

<!-- Mobile Search Modal -->
<div id="mobileSearchModal" class="fixed inset-0 bg-white z-[60] hidden flex-col">
    <!-- Search Header -->
    <div class="bg-white shadow-sm py-4 px-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Search</h2>
            <button id="closeMobileSearch" class="p-2 text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Search Input -->
        <div class="relative mt-4">
            <input 
                type="text"
                id="mobileSearchInput"
                placeholder="Search for a country or region..."
                class="w-full bg-gray-100 rounded-[20px] px-4 py-3 pr-12 text-[16px] font-normal text-[#101010] font-['Satoshi'] leading-[20px] focus:outline-none focus:ring-2 focus:ring-[#f4633a] border-0">
            
            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 p-1">
                <img src="{{ asset('assets/images/img_frame_black_900_01.svg') }}" alt="search" class="w-5 h-5">
            </button>
        </div>
    </div>

    <!-- Search Results -->
    <div id="mobileSearchResults" class="flex-1 overflow-y-auto p-4">
        <!-- Popular Locations (shown by default) -->
        <div id="mobilePopularLocations">
            <h3 class="text-gray-700 font-semibold text-sm mb-3">Popular Locations</h3>
            <div class="space-y-2">
                <!-- Popular locations will be loaded here -->
            </div>
        </div>

        <!-- Search Results -->
        <div id="mobileSearchResultsList" class="hidden">
            <h3 class="text-gray-700 font-semibold text-sm mb-3">Search Results</h3>
            <div class="space-y-2">
                <!-- Search results will be loaded here -->
            </div>
        </div>

        <!-- No Results -->
        <div id="mobileNoResults" class="hidden text-center py-8">
            <p class="text-gray-500">No results found</p>
        </div>

        <!-- Loading -->
        <div id="mobileSearchLoading" class="hidden text-center py-8">
            <p class="text-gray-500">Searching...</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileSearchTrigger = document.getElementById('mobileSearchTrigger');
    const mobileSearchModal = document.getElementById('mobileSearchModal');
    const closeMobileSearch = document.getElementById('closeMobileSearch');
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    const mobileSearchResults = document.getElementById('mobileSearchResults');
    const mobilePopularLocations = document.getElementById('mobilePopularLocations');
    const mobileSearchResultsList = document.getElementById('mobileSearchResultsList');
    const mobileNoResults = document.getElementById('mobileNoResults');
    const mobileSearchLoading = document.getElementById('mobileSearchLoading');

    let searchTimeout;

    // Open mobile search modal
    mobileSearchTrigger.addEventListener('click', function() {
        mobileSearchModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        mobileSearchInput.focus();
        loadPopularLocations();
    });

    // Close mobile search modal
    closeMobileSearch.addEventListener('click', closeModal);
    
    // Close modal when clicking outside (on overlay)
    mobileSearchModal.addEventListener('click', function(e) {
        if (e.target === mobileSearchModal) {
            closeModal();
        }
    });

    // Handle search input
    mobileSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = this.value.trim();

        if (searchTerm.length === 0) {
            showPopularLocations();
            return;
        }

        if (searchTerm.length < 2) {
            mobileSearchResultsList.classList.add('hidden');
            mobileNoResults.classList.add('hidden');
            mobilePopularLocations.classList.remove('hidden');
            return;
        }

        mobileSearchLoading.classList.remove('hidden');
        mobilePopularLocations.classList.add('hidden');
        mobileSearchResultsList.classList.add('hidden');
        mobileNoResults.classList.add('hidden');

        searchTimeout = setTimeout(() => {
            performMobileSearch(searchTerm);
        }, 300);
    });

    function closeModal() {
        mobileSearchModal.classList.add('hidden');
        document.body.style.overflow = '';
        mobileSearchInput.value = '';
        showPopularLocations();
    }

    function loadPopularLocations() {
        fetch(`/search?q=`)
            .then(response => response.json())
            .then(data => {
                displayPopularLocations(data);
            })
            .catch(error => {
                console.error('Error loading popular locations:', error);
            });
    }

    function displayPopularLocations(results) {
        const container = mobilePopularLocations.querySelector('.space-y-2');
        
        if (!results.bundles || results.bundles.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-4">No popular locations found</p>';
            return;
        }

        let html = '';
        results.bundles.forEach(bundle => {
            html += `
                <a href="/plans/${bundle.slug}" 
                   class="flex items-center gap-3 hover:bg-gray-100 p-3 rounded-md transition-colors"
                   onclick="closeModal()">
                    <img src="${bundle.image}" alt="${bundle.name}" class="w-6 h-6 object-contain rounded">
                    <span class="text-sm font-normal text-gray-900">${bundle.name}</span>
                </a>
            `;
        });

        container.innerHTML = html;
    }

    function performMobileSearch(searchTerm) {
        fetch(`/search?q=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                displayMobileSearchResults(data);
            })
            .catch(error => {
                console.error('Search error:', error);
                mobileSearchLoading.classList.add('hidden');
                mobileNoResults.classList.remove('hidden');
            });
    }

    function displayMobileSearchResults(results) {
        mobileSearchLoading.classList.add('hidden');

        if (!results.bundles || results.bundles.length === 0) {
            mobileNoResults.classList.remove('hidden');
            return;
        }

        const container = mobileSearchResultsList.querySelector('.space-y-2');
        let html = '';

        results.bundles.forEach(bundle => {
            html += `
                <a href="/plans/${bundle.slug}" 
                   class="flex items-center gap-3 hover:bg-gray-100 p-3 rounded-md transition-colors"
                   onclick="closeModal()">
                    <img src="${bundle.image}" alt="${bundle.name}" class="w-6 h-6 object-contain rounded">
                    <span class="text-sm font-normal text-gray-900">${bundle.name}</span>
                </a>
            `;
        });

        container.innerHTML = html;
        mobileSearchResultsList.classList.remove('hidden');
    }

    function showPopularLocations() {
        mobilePopularLocations.classList.remove('hidden');
        mobileSearchResultsList.classList.add('hidden');
        mobileNoResults.classList.add('hidden');
        mobileSearchLoading.classList.add('hidden');
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !mobileSearchModal.classList.contains('hidden')) {
            closeModal();
        }
    });
});

// Make closeModal available globally for onclick events
function closeModal() {
    const modal = document.getElementById('mobileSearchModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}
</script>