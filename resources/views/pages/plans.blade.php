@extends('layouts.app')

@section('title', 'Our Plans – Esimira')
@section('meta_description', 'Discover flexible eSIM plans for 200+ countries. Choose local, regional, or global data plans with Esimira.')
@section('meta_keywords', 'esim plans, travel data, esimira plans, global esim, local esim, regional esim')

@section('content')
<div class="flex flex-col justify-start items-center w-full h-[68vh] bg-[url('{{ asset('assets/images/plan_home_banner.png') }}')] bg-cover bg-center relative">
    <section id="section-hero" class="hero-section w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-[86px] relative z-10 mt-24">
        <div class="container hero-content">
            <h1 class="hero-title">
                <span class="hero-title-light">Our</span> <span class="hero-title-bold text-orange">Plan</span>
            </h1>
            <p class="breadcrumbs">Home → Our Plan</p>

            <!-- Search Component -->
            @include('partials._search')
        </div>
    </section>
</div>

<!-- Pricing Section -->
<section class="w-full py-16 sm:py-20 lg:py-24 bg-[url('{{ asset('assets/images/third_banner.png') }}')] bg-cover bg-center">
    <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-2 mb-4">
                <img src="{{ asset('assets/images/img_group_5911.svg') }}" alt="plans icon" class="w-[14px] h-[16px]">
                <span class="text-[12px] font-medium text-[#000000] font-['Satoshi'] leading-[17px] uppercase">our Plans</span>
            </div>
            <h2 class="text-[28px] sm:text-[32px] lg:text-[35px] font-normal text-[#101010] font-['Satoshi'] leading-[36px] sm:leading-[40px] lg:leading-[43px] capitalize text-center">
                <span>flexible pricing </span>
                <span class="font-bold text-[#f4633a]">plan</span>
                <span> that<br>include business</span>
            </h2>
        </div>

        <!-- Plan Categories -->
        <div id="plan-categories" class="flex flex-col items-center gap-6 mb-8">
            <div class="w-full overflow-x-auto scrollbar-hide">
                <div id="tabs" class="flex justify-start sm:justify-center items-center gap-6 min-w-max px-4 sm:px-0 relative">
                    @php
                        $tabs = [
                            'local' => 'Local',
                            'regional' => 'Regional',
                            'global' => 'Global',
                            'gcc' => 'Middle East',
                            'monthly' => 'Lifetime',
                        ];
                    @endphp

                    @foreach ($tabs as $key => $label)
                        <button class="plan-tab {{ $type === $key ? 'active-tab font-bold text-[#f4633a]' : 'font-normal text-[#101010]' }} text-[16px] sm:text-[18px] whitespace-nowrap transition-all"
                                data-tab="{{ $key }}" onclick="switchTab('{{ $key }}')">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="w-full relative mt-2">
                <div class="w-full h-[1px] bg-[#d9d9d9]"></div>
                <div id="tab-indicator" class="absolute top-0 left-0 w-[100px] h-[2px] bg-[#f4633a] shadow-[0px_2px_5px_#f4633a99] transition-all duration-300"></div>
            </div>
        </div>

        <!-- Tab Content -->
        <div id="tab-content" class="text-center">
            <h3 class="text-[20px] font-semibold text-[#101010] mb-2" id="tab-title">
                {{ $tabs[$type] ?? 'Local eSIMs' }}
            </h3>
            <p class="text-gray-600 mb-6" id="tab-description">
                {{ [
                    'local' => 'Connect locally with affordable data plans in over 140+ countries',
                    'regional' => 'Stay connected across multiple countries in the same region',
                    'global' => 'Worldwide coverage with our comprehensive global plans',
                    'gcc' => 'Special plans for Gulf Cooperation Council countries',
                    'monthly' => 'Long-term connectivity solutions with lifetime subscriptions',
                ][$type] ?? 'Connect locally with affordable data plans in over 140+ countries' }}
            </p>
        </div>

        <!-- Bundles Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8" id="bundles-grid">
            @foreach ($bundles as $bundle)
                <div class="bg-[#fdfdfd] rounded-[10px] p-3 shadow-[0px_0px_5px_#00000033] pricing-card hover:shadow-[0px_0px_10px_#f4633a33] transition-all duration-300 cursor-pointer"
                     onclick="window.location.href='{{ url('/plans/' . $bundle['slug']) }}'">
                    <div class="flex items-center gap-3">
                        <img src="{{ $bundle['image'] }}" alt="{{ $bundle['name'] }}" class="w-[40px] sm:w-[50px] h-[40px] sm:h-[50px] object-contain" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('/assets/images/default-bundle.svg') }}'">
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="text-[18px] sm:text-[20px] font-bold text-[#000000] font-['Satoshi'] leading-[24px] sm:leading-[27px]">
                                    {{ $bundle['name'] }}
                                </h3>
                                <div class="relative w-[6px] h-[12px]">
                                    <img src="{{ asset('assets/images/img_vector_gray_500_12.svg') }}" alt="arrow" class="w-[6px] h-[12px] gray-icon absolute inset-0">
                                    <img src="{{ asset('assets/images/img_vector_deep_orange_a200.svg') }}" alt="arrow" class="w-[6px] h-[12px] orange-icon absolute inset-0 hidden">
                                </div>
                            </div>

                            @if ($bundle['min_price'])
                                <p class="text-[12px] sm:text-[14px] font-normal text-[#828282] font-['Satoshi'] leading-[17px] sm:leading-[19px]">
                                    From
                                    <span 
                                        data-price-usd="{{ $bundle['price_in_usd'] ?? $bundle['min_price'] }}"
                                        class="price-element font-medium text-[#101010]">
                                        {{ $bundle['currency_sign'] ?? '$' }}{{ number_format($bundle['min_price'], 2) }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($bundles->count() === 0)
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500 text-lg">No plans found for this category.</p>
                </div>
            @endif
        </div>
    </div>

    @include('partials._footer')
</section>

<script>
// ⚡ ALL DATA LOADED ON PAGE LOAD - No API calls needed!
const allPlansData = @json($allPlans);

const tabTitles = {
    'local': 'Local eSIMs',
    'regional': 'Regional eSIMs',
    'global': 'Global eSIMs',
    'gcc': 'GCC eSIMs',
    'monthly': 'Lifetime'
};

const tabDescriptions = {
    'local': 'Connect locally with affordable data plans in over 140+ countries',
    'regional': 'Stay connected across multiple countries in the same region',
    'global': 'Worldwide coverage with our comprehensive global plans',
    'gcc': 'Special plans for Gulf Cooperation Council countries',
    'monthly': 'Long-term connectivity solutions with lifetime subscriptions'
};

let currentTab = '{{ $type }}';

document.addEventListener('DOMContentLoaded', function() {
    updateTabIndicator();
    window.addEventListener('resize', updateTabIndicator);
    initPricingCardHover();
    
    console.log('⚡ All plans data loaded:', Object.keys(allPlansData).length + ' categories');
});

function switchTab(tabType) {
    if (currentTab === tabType) return;

    currentTab = tabType;

    // ⚡ INSTANT TAB SWITCHING - No API calls, no loading!
    if (allPlansData[tabType]) {
        displayBundles(allPlansData[tabType]);
        updateTabContent(tabType);
        updateTabUI(tabType);
        updateUrl(tabType);
    } else {
        // Fallback - show empty state
        document.getElementById('bundles-grid').innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-500 text-lg">No plans found for this category.</p></div>';
    }
}

function updateTabContent(tabType) {
    document.getElementById('tab-title').textContent = tabTitles[tabType] || 'Local eSIMs';
    document.getElementById('tab-description').textContent = tabDescriptions[tabType];
}

function updateTabUI(activeTab) {
    document.querySelectorAll('.plan-tab').forEach(tab => {
        tab.classList.toggle('active-tab', tab.dataset.tab === activeTab);
        tab.classList.toggle('font-bold', tab.dataset.tab === activeTab);
        tab.classList.toggle('text-[#f4633a]', tab.dataset.tab === activeTab);
        tab.classList.toggle('font-normal', tab.dataset.tab !== activeTab);
        tab.classList.toggle('text-[#101010]', tab.dataset.tab !== activeTab);
    });
    updateTabIndicator();
}

function updateTabIndicator() {
    const activeTab = document.querySelector('.plan-tab.active-tab');
    const indicator = document.getElementById('tab-indicator');
    if (activeTab && indicator) {
        const rect = activeTab.getBoundingClientRect();
        const parentRect = activeTab.parentElement.getBoundingClientRect();
        indicator.style.width = `${rect.width}px`;
        indicator.style.left = `${rect.left - parentRect.left}px`;
    }
}

function updateUrl(tabType) {
    const url = new URL(window.location);
    url.searchParams.set('type', tabType);
    window.history.pushState({}, '', url);
}

function displayBundles(bundles) {
    const grid = document.getElementById('bundles-grid');
    
    if (!bundles || bundles.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-500 text-lg">No plans found for this category.</p></div>';
        return;
    }

    grid.innerHTML = bundles.map(bundle => `
        <div class="bg-[#fdfdfd] rounded-[10px] p-3 shadow-[0px_0px_5px_#00000033] pricing-card hover:shadow-[0px_0px_10px_#f4633a33] transition-all duration-300 cursor-pointer"
             onclick="window.location.href='/plans/${bundle.slug}'">
            <div class="flex items-center gap-3">
                <img src="${bundle.image}" alt="${bundle.name}" class="w-[40px] sm:w-[50px] h-[40px] sm:h-[50px] object-contain" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('/assets/images/default-bundle.svg') }}'">
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="text-[18px] sm:text-[20px] font-bold text-[#000000] font-['Satoshi'] leading-[24px] sm:leading-[27px]">
                            ${bundle.name}
                        </h3>
                        <div class="relative w-[6px] h-[12px]">
                            <img src="{{ asset('assets/images/img_vector_gray_500_12.svg') }}" alt="arrow" class="w-[6px] h-[12px] gray-icon absolute inset-0">
                            <img src="{{ asset('assets/images/img_vector_deep_orange_a200.svg') }}" alt="arrow" class="w-[6px] h-[12px] orange-icon absolute inset-0 hidden">
                        </div>
                    </div>
                    ${bundle.min_price ? `
                        <p class="text-[12px] sm:text-[14px] font-normal text-[#828282] font-['Satoshi'] leading-[17px] sm:leading-[19px]">
                            From
                            <span data-price-usd="${bundle.price_in_usd || bundle.min_price}" class="price-element font-medium text-[#101010]">
                                ${bundle.currency_sign || '$'}${parseFloat(bundle.min_price).toFixed(2)}
                            </span>
                        </p>
                    ` : ''}
                </div>
            </div>
        </div>
    `).join('');

    initPricingCardHover();
    
    // Update currency conversion if available
    if (window.CurrencyHelper && typeof window.CurrencyHelper.updateAllPrices === 'function') {
        window.CurrencyHelper.updateAllPrices();
    }
}

function initPricingCardHover() {
    document.querySelectorAll('.pricing-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.querySelector('.gray-icon')?.classList.add('hidden');
            card.querySelector('.orange-icon')?.classList.remove('hidden');
        });
        card.addEventListener('mouseleave', () => {
            card.querySelector('.gray-icon')?.classList.remove('hidden');
            card.querySelector('.orange-icon')?.classList.add('hidden');
        });
    });
}

// Handle browser back/forward navigation
window.addEventListener('popstate', () => {
    const params = new URLSearchParams(location.search);
    const type = params.get('type') || 'local';
    if (type !== currentTab) switchTab(type);
});
</script>
@endsection