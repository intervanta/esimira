<!-- Persistent Search Bar at Bottom -->
<div class="fixed bottom-0 left-0 right-0 z-40 py-4 bg-transparent">
    <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative max-w-2xl mx-auto">
            <!-- Search Input -->
            <!-- Anti-autofill trap -->
            <div style="position:absolute; left:-9999px;">
                <input type="text" autocomplete="nope">
                <input type="password" autocomplete="nope">
            </div>

            <input type="search" id="searchInput" name="q" placeholder="Search for a country or region..."
                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" data-lpignore="true"
                data-form-type="other" readonly onfocus="this.removeAttribute('readonly')"
                class="w-full bg-[#ffffff] rounded-[26px] px-6 py-4 pr-16 text-[14px] sm:text-[16px] font-normal text-[#101010] font-['Satoshi'] leading-[20px] sm:leading-[22px] shadow-[0px_2px_30px_#1d0a0033] focus:outline-none focus:ring-2 focus:ring-[#f4633a]">
            <!-- Search Icon -->
            <button class="absolute right-4 top-1/2 transform -translate-y-1/2 p-2">
                <img src="/assets/images/img_frame_black_900_01.svg" alt="search" class="w-[20px] sm:w-[22px] h-auto">
            </button>

            <!-- Search Results - Positioned ABOVE the search input -->
            <div id="searchList"
                class="absolute left-0 right-0 bottom-full mb-2 bg-white rounded-xl shadow-lg max-h-[300px] overflow-y-auto hidden border border-gray-100 z-50">
                <!-- Content populated from GLOBAL popularBundles variable -->
            </div>
        </div>
    </div>
</div>

<!-- Your regular page content starts here -->
<div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 pb-32">
    <!-- Your other page content goes here -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchList = document.getElementById('searchList');
        let searchTimeout;
        
        // ⚡ GLOBAL popularBundles variable available everywhere
        const popularBundlesData = @json($popularBundles ?? []);
        const isDataLoaded = popularBundlesData.length > 0;

        // ⚡ PRE-RENDER popular data immediately on page load
        preRenderPopularData(popularBundlesData);

        // Show preloaded data instantly when input is focused
        searchInput.addEventListener('focus', function() {
            if (isDataLoaded) {
                // ⚡ INSTANT: Show preloaded data
                displayPopularBundles();
            } else {
                // Show loading if no data available
                showLoadingState();
            }
        });

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();

            if (searchTerm.length === 0) {
                // Show preloaded popular data
                if (isDataLoaded) {
                    displayPopularBundles();
                } else {
                    showLoadingState();
                }
                return;
            }

            if (searchTerm.length < 2) {
                searchList.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetchSearchResults(searchTerm);
            }, 200);
        });

        searchInput.addEventListener('blur', function() {
            setTimeout(() => {
                searchList.classList.add('hidden');
            }, 300);
        });

        // ⚡ PRE-RENDER popular data so it's ready to show instantly
        function preRenderPopularData(data) {
            if (!data || data.length === 0) {
                searchList.innerHTML = '<div class="p-4 text-gray-500 text-center">No popular locations found</div>';
                console.log('⚠️ No popular data available');
                return;
            }

            let html = `
                <h3 class="px-4 pt-3 pb-1 text-gray-700 font-semibold text-sm">Popular Locations</h3>
                <hr class="border-gray-200 mb-2">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 px-4 pb-4">
            `;

            data.forEach(bundle => {
                html += `
                    <a href="/plans/${bundle.slug}" class="flex items-center gap-2 hover:bg-gray-100 p-2 rounded-md transition-colors group">
                        <img src="${bundle.image}" alt="${bundle.name}" class="w-6 h-6 object-contain" onerror="this.src='/assets/images/default-country.svg'">
                        <span class="text-sm font-normal text-gray-900 group-hover:text-[#f4633a] transition-colors">${bundle.name}</span>
                    </a>
                `;
            });

            html += '</div>';
            searchList.innerHTML = html;
            
            console.log('✅ Popular data pre-rendered and ready to show!', data.length + ' locations');
        }

        function showLoadingState() {
            searchList.innerHTML = `
                <div class="p-4 text-center">
                    <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-[#f4633a]"></div>
                    <p class="text-gray-500 text-sm mt-1">Loading popular locations...</p>
                </div>
            `;
            searchList.classList.remove('hidden');
        }

        function displayPopularBundles() {
            // Data is already pre-rendered, just show it
            searchList.classList.remove('hidden');
            console.log('🎯 Showing preloaded popular data instantly!');
        }

        function fetchSearchResults(searchTerm) {
            // Show loading state for search
            searchList.innerHTML = `
                <div class="p-4 text-center">
                    <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-[#f4633a]"></div>
                    <p class="text-gray-500 text-sm mt-1">Searching...</p>
                </div>
            `;
            searchList.classList.remove('hidden');

            fetch(`/search?q=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchList.innerHTML = '<div class="p-4 text-gray-500 text-center">Search failed</div>';
                });
        }

        function displaySearchResults(results) {
            if (!results.bundles || results.bundles.length === 0) {
                searchList.innerHTML = '<div class="p-4 text-gray-500 text-center">No results found</div>';
            } else {
                let html = `
                    <h3 class="px-4 pt-3 pb-1 text-gray-700 font-semibold text-sm">Search Results</h3>
                    <hr class="border-gray-200 mb-2">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 px-4 pb-4">
                `;

                results.bundles.forEach(bundle => {
                    html += `
                        <a href="/plans/${bundle.slug}" class="flex items-center gap-2 hover:bg-gray-100 p-2 rounded-md transition-colors group">
                            <img src="${bundle.image}" alt="${bundle.name}" class="w-6 h-6 object-contain" onerror="this.src='/assets/images/default-country.svg'">
                            <span class="text-sm font-normal text-gray-900 group-hover:text-[#f4633a] transition-colors">${bundle.name}</span>
                        </a>
                    `;
                });

                html += '</div>';
                searchList.innerHTML = html;
            }

            searchList.classList.remove('hidden');
        }

        // Close search when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !searchList.contains(event.target)) {
                searchList.classList.add('hidden');
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            // Ctrl+K or Cmd+K to focus search
            if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
                event.preventDefault();
                searchInput.focus();
            }
            
            // Escape to close search
            if (event.key === 'Escape') {
                searchList.classList.add('hidden');
                searchInput.blur();
            }
        });

        // Show status in console for debugging
        console.log('🔄 Search system initialized');
        console.log('✅ Popular data:', isDataLoaded ? popularBundlesData.length + ' locations ready' : 'Not available');
    });
</script>