@extends('layouts.app')

@section('title', 'Our Plans – Esimira')
@section('meta_description', 'Discover flexible eSIM plans for 200+ countries. Choose local, regional, or global data plans with Esimira.')
@section('meta_keywords', 'esim plans, travel data, esimira plans, global esim, local esim, regional esim')

@section('content')
<div class="flex flex-col justify-start items-center w-full h-[66vh] bg-[url('{{ asset('assets/images/plan_home_banner.png') }}')] bg-cover bg-center relative">
   <section id="hero" class="hero-section max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-[196px] relative z-10">
        <div class="container hero-content">
            <h1 class="hero-title">
                <span class="hero-title-light">Our</span> <span class="hero-title-bold text-orange">Plan</span>
            </h1>
            <p class="breadcrumbs">Home → Our Plan</p>
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
      <!-- Tabs -->
        <div class="flex flex-col items-center gap-6 mb-8">
            <div class="w-full overflow-x-auto scrollbar-hide">
                <div id="tabs" class="flex justify-start sm:justify-center items-center gap-6 min-w-max px-4 sm:px-0">
                    <button class="plan-tab {{ $initialType === 'local' ? 'active-tab font-bold text-[#f4633a]' : 'font-normal text-[#101010]' }} transition-all" data-tab="local">Local</button>
                    <button class="plan-tab {{ $initialType === 'regional' ? 'active-tab font-bold text-[#f4633a]' : 'font-normal text-[#101010]' }} transition-all" data-tab="regional">Regional</button>
                    <button class="plan-tab {{ $initialType === 'global' ? 'active-tab font-bold text-[#f4633a]' : 'font-normal text-[#101010]' }} transition-all" data-tab="global">Global</button>
                    <button class="plan-tab {{ $initialType === 'gcc' ? 'active-tab font-bold text-[#f4633a]' : 'font-normal text-[#101010]' }} transition-all" data-tab="gcc">Middle East</button>
                    <button class="plan-tab {{ $initialType === 'monthly' ? 'active-tab font-bold text-[#f4633a]' : 'font-normal text-[#101010]' }} transition-all" data-tab="monthly">Lifetimes</button>
                </div>
            </div>

            <!-- Indicator -->
            <div class="w-full relative mt-2">
                <div class="w-full h-[1px] bg-[#d9d9d9]"></div>
                <div id="tab-indicator" class="absolute top-0 left-0 w-[100px] h-[2px] bg-[#f4633a] shadow-[0px_2px_5px_#f4633a99] transition-all duration-300"></div>
            </div>
        </div>

        <!-- Tab Intro Text -->
        <!-- <div id="tab-content" class="text-center">
            <h3 class="text-[20px] font-semibold text-[#101010] mb-2" id="tab-title">
                {{ $initialType === 'local' ? 'Local' : ($initialType === 'regional' ? 'Regional' : ($initialType === 'global' ? 'Global' : ($initialType === 'gcc' ? 'Middle East' : 'Lifetime'))) }}
            </h3>
            <p class="text-gray-600 mb-6" id="tab-desc">
                {{ $initialType === 'local' ? 'Connect locally with affordable data plans in over 150+ countries' : 
                   ($initialType === 'regional' ? 'Stay connected across multiple countries in the same region' : 
                   ($initialType === 'global' ? 'Worldwide coverage with our comprehensive global plans' : 
                   ($initialType === 'gcc' ? 'Special plans for Gulf Cooperation Council countries' : 
                   'Long-term connectivity solutions with lifetime subscriptions'))) }}
            </p>
        </div> -->

        <!-- Grid - Initial server-rendered content -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8" id="bundles-grid">
    @foreach($initialBundles as $bundle)
        <div
            class="bg-[#fdfdfd] rounded-[10px]
                   p-2
                   shadow-[0px_0px_5px_#00000033]
                   pricing-card hover:shadow-[0px_0px_10px_#f4633a33]
                   transition-all duration-300 cursor-pointer
                   h-[75px] flex"
            onclick="window.location.href='/plans/{{ $bundle['slug'] }}'"
        >
            <div class="flex items-center gap-2 w-full h-full">
                <img
                    src="{{ $bundle['image'] }}"
                    alt="{{ $bundle['name'] }}"
                    class="w-[36px] sm:w-[44px] h-[36px] sm:h-[44px] ml-[10px] object-contain flex-shrink-0"
                    loading="lazy"
                    onerror="this.onerror=null; this.src='{{ asset('/assets/images/default-bundle.svg') }}'"
                >

                <div class="flex-1 min-w-0 flex flex-col justify-center">
                    <div class="flex justify-between items-start gap-2">
                        <h3
                            class="text-[16px] sm:text-[18px] font-bold text-[#000000]
                                   leading-[1.1]
                                   overflow-hidden text-ellipsis
                                   [display:-webkit-box]
                                   [-webkit-line-clamp:2]
                                   [-webkit-box-orient:vertical]"
                        >
                            {{ $bundle['name'] }}
                        </h3>

                        <div class="relative w-[8px] h-[14px] flex-shrink-0 mt-[12px] me-[4px]">
                            <img src="{{ asset('assets/images/img_vector_gray_500_12.svg') }}" class="gray-icon absolute inset-0">
                            <img src="{{ asset('assets/images/img_vector_deep_orange_a200.svg') }}" class="orange-icon absolute inset-0 hidden">
                        </div>
                    </div>

                    @if($bundle['min_price'])
                        <p
                            class="text-[11px] sm:text-[12px] text-[#828282]
                                   leading-none mt-[2px]
                                   whitespace-nowrap overflow-hidden text-ellipsis"
                        >
                            From
                            <span
                                data-price-usd="{{ $bundle['price_in_usd'] ?? $bundle['min_price'] }}"
                                class="price-element font-medium text-[#101010]"
                            >
                                {{ $bundle['currency_sign'] ?? '$' }}{{ number_format($bundle['min_price'], 2) }}
                            </span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

            @if ($initialBundles->count() === 0)
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500 text-lg">No plans found for this category.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const bundlesGrid = document.getElementById("bundles-grid");
        const tabs = document.querySelectorAll(".plan-tab");
        const tabIndicator = document.getElementById("tab-indicator");
        const tabTitle = document.getElementById("tab-title");
        const tabDesc = document.getElementById("tab-desc");
        const seeAllBtn = document.getElementById("see-all-btn");

        // ⚡ All plans data passed from server for instant switching
        const allPlansData = @json($allPlans ?? []);

        // const tabInfo = {
        //     local: { title: "Local", desc: "Connect locally with affordable data plans in over 150+ countries" },
        //     regional: { title: "Regional", desc: "Stay connected across multiple countries in the same region" },
        //     global: { title: "Global", desc: "Worldwide coverage with our comprehensive global plans" },
        //     gcc: { title: "Middle East", desc: "Special plans for Gulf Cooperation Council countries" },
        //     monthly: { title: "Lifetime", desc: "Long-term connectivity solutions with lifetime subscriptions" },
        // };

        function loadPlans(type) {
            // ⚡ INSTANT SWITCHING: Use preloaded data if available
            if (allPlansData && allPlansData[type]) {
                const bundles = allPlansData[type].slice(0, 28); // Limit to 8 for home page
                displayBundles(bundles);
            } else {
                // Fallback to API call
                bundlesGrid.innerHTML = `
                    <div class="col-span-full text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#f4633a]"></div>
                        <p class="text-gray-500 mt-2">Loading plans...</p>
                    </div>
                `;

                fetch(`/plans/popular-data?type=${type}`)
                    .then(r => r.json())
                    .then(data => displayBundles(data.bundles))
                    .catch(() => {
                        bundlesGrid.innerHTML = `<div class="col-span-full text-center py-8 text-gray-500 text-lg">Error loading plans.</div>`;
                    });
            }
        }

        function displayBundles(bundles) {
            if (!bundles?.length) {
                bundlesGrid.innerHTML = `<div class="col-span-full text-center py-8 text-gray-500 text-lg">No plans found.</div>`;
                return;
            }

            bundlesGrid.innerHTML = bundles.map(b => `<div
  class="bg-[#fdfdfd] rounded-[10px] p-3
         shadow-[0px_0px_5px_#00000033]
         hover:shadow-[0px_0px_10px_#f4633a33]
         transition-all duration-300 cursor-pointer
         w-full h-[75px] flex"
  onclick="window.location.href='/plans/${b.slug}'"
>
  <div class="flex items-center gap-3 w-full h-full">
    <img
      src="${b.image}"
      alt="${b.name}"
       class="w-[36px] sm:w-[44px] h-[36px] sm:h-[44px] ml-[10px] object-contain flex-shrink-0"
      loading="lazy"
      onerror="this.onerror=null; this.src='{{ asset('/assets/images/default-bundle.svg') }}'"
    />

    <div class="flex flex-col justify-center flex-1 min-w-0">
      <div class="flex justify-between items-start gap-2">
        <h3
          class="text-[18px] sm:text-[20px] font-bold text-black
                 leading-tight
                 overflow-hidden text-ellipsis
                 [display:-webkit-box]
                 [-webkit-line-clamp:2]
                 [-webkit-box-orient:vertical]"
        >
          ${b.name}
        </h3>

        <div relative w-[8px] h-[14px] flex-shrink-0 mt-[12px] me-[4px]>
          <img
            src="{{ asset('assets/images/img_vector_gray_500_12.svg') }}"
            class="gray-icon absolute inset-0"
          />
          <img
            src="{{ asset('assets/images/img_vector_deep_orange_a200.svg') }}"
            class="orange-icon absolute inset-0 hidden"
          />
        </div>
      </div>

      ${b.min_price ? `
        <p class="text-[12px] sm:text-[14px] text-[#828282]
                  mt-1 whitespace-nowrap overflow-hidden text-ellipsis">
          From
          <span
            data-price-usd="${b.price_in_usd || b.min_price}"
            class="price-element font-medium text-[#101010]"
          >
            ${b.currency_sign || '$'}${parseFloat(b.min_price).toFixed(2)}
          </span>
        </p>
      ` : ''}
    </div>
  </div>
</div>

            `).join("");

            initHoverEffect();
            if (window.CurrencyHelper && typeof window.CurrencyHelper.updateAllPrices === 'function') {
                window.CurrencyHelper.updateAllPrices();
            }
        }

        function initHoverEffect() {
            document.querySelectorAll(".pricing-card").forEach(card => {
                card.onmouseenter = () => {
                    card.querySelector(".gray-icon")?.classList.add("hidden");
                    card.querySelector(".orange-icon")?.classList.remove("hidden");
                };
                card.onmouseleave = () => {
                    card.querySelector(".gray-icon")?.classList.remove("hidden");
                    card.querySelector(".orange-icon")?.classList.add("hidden");
                };
            });
        }

        function updateIndicator() {
            const active = document.querySelector(".plan-tab.active-tab");
            if (!active) return;
            const rect = active.getBoundingClientRect();
            const parent = active.parentElement.getBoundingClientRect();
            tabIndicator.style.left = (rect.left - parent.left) + "px";
            tabIndicator.style.width = rect.width + "px";
        }

        tabs.forEach(tab => {
            tab.addEventListener("click", () => {
                const type = tab.dataset.tab;

                tabs.forEach(t => t.classList.remove("active-tab", "font-bold", "text-[#f4633a]"));
                tabs.forEach(t => t.classList.add("font-normal", "text-[#101010]"));
                tab.classList.add("active-tab", "font-bold", "text-[#f4633a]");
                tab.classList.remove("font-normal", "text-[#101010]");

                // tabTitle.textContent = tabInfo[type].title;
                // tabDesc.textContent = tabInfo[type].desc;
                seeAllBtn.href = `/plans?type=${type}`;

                updateIndicator();
                loadPlans(type);
            });
        });

        // Initialize
        updateIndicator();
        window.addEventListener("resize", updateIndicator);
    });
</script>
 @include('partials._footer')
@endsection
