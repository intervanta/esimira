@extends('layouts.app')

@section('title', $bundle->name . ' eSIM Plan – Esimira')
@section('meta_description',
    $bundle->description ?:
    'Discover flexible eSIM plans for ' .
    $bundle->name .
    ' with
    Esimira.')
@section('meta_keywords', 'esim ' . $bundle->name . ', ' . $bundle->name . ' data plan, travel data, esimira')

@section('content')
  <div class="flex flex-col justify-start items-center h-[66vh] w-full  bg-[url('../assets/images/about-bg.png')] bg-cover bg-center relative">
    <section id="hero" class="hero-section max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-[196px] relative z-10">
        <div class="container hero-content">
            <h1><span class="font-light">{{ $bundle->name }} Esim</span> <span
                            class="text-primary font-medium">Plan</span></h1>
            <p class="breadcrumbs">Our Plan → {{ $bundleType }} → {{ $bundle->name }} eSIM plan</p>
        </div>
    </section>
</div>

    <section id="section-plans" class="plans-section">
        <div class="container">
            <div class="content-wrapper">
                <div class="plans-title mb-10">
                    <div class="plans-subtitle">
                        <div class="icon-wrapper">
                            <img src="{{ asset('assets/images/75_16749.svg') }}" alt="Personal Plans Icon">
                        </div>
                        <span>Personal Plans</span>
                    </div>
                    <h2>
                        <img src="{{ $bundle->image ? asset('storage/' . $bundle->image) : asset('assets/images/75_16752.svg') }}"
                            alt="{{ $bundle->name }} Flag Icon" class="w-6 h-6">
                        <span class="font-light">eSIM For</span> <span
                            class="text-primary font-bold">{{ $bundle->name }}</span>
                    </h2>
                </div>

                <div class="plans-main-content">
                    <div class="plans-image">
                        <img src="{{ asset('assets/image.jpg') }}"
                            alt="View of {{ $bundle->name }}">
                    </div>
                    <div class="plans-selector">
                       <div class="flex flex-col gap-1">
    <h3 class="m-0 leading-tight">
        Choose your eSIM data plan for {{ $bundle->name }}
    </h3>
    <p class="mt-2 text-sm leading-snug">
        {{ $bundle->description ?: 'Stay connected with a reliable, affordable eSIM for ' . $bundle->name . '—no physical SIM or roaming hassles.' }}
    </p>
</div>


                        <!-- Location & Coverage Info Tips -->
<div class="info-div">
    <h3 class="mb-4">Plan Details</h3>
                        <div class="info-tips-grid mb-1">
                            <!-- Privacy IP Card -->
                            <div class="info-tip-card clickable" data-modal="privacy-ip-modal">
                                <div class="tip-icon">
                                    <i class="fa-solid fa-location-dot icon-orange"></i>
                                </div>
                                <div class="tip-content">
                                    <span class="tip-label">PRIVACY IP</span>
                                    <span class="tip-value">{{ $bundle->privacy_ip }}</span>
                                </div>
                            </div>

                            <!-- Countries & Networks Card -->
                            <div class="info-tip-card clickable" data-modal="countries-networks-modal">
                                <div class="tip-icon">
                                    <i class="fa-solid fa-globe-americas icon-orange"></i>
                                </div>
                                <div class="tip-content">
                                    <span class="tip-label">Global Networks</span>
                                    <span class="tip-value">{{ count($networks) }} countries</span>
                                </div>
                            </div>

                            <!-- Privacy Protected Card -->
                            <div class="info-tip-card clickable" data-modal="privacy-protected-modal">
                                <div class="tip-icon">
                                    <i class="fa-solid fa-shield-halved icon-orange"></i>
                                </div>
                                <div class="tip-content">
                                    <span class="tip-label">Privacy Secured</span>
                                    <span class="tip-value">Secure Connection</span>
                                </div>
                            </div>

                            <!-- Region Card -->
                            <div class="info-tip-card clickable" data-modal="region-modal">
                                <div class="tip-icon">
                                    <i class="fa-solid fa-map icon-orange"></i>
                                </div>
                                <div class="tip-content">
                                    <span class="tip-label">Region</span>
                                    <span class="tip-value">{{ $bundle->region }}</span>
                                </div>
                            </div>
                        </div>
</div>
                      
                        <div class="plan-options-grid" id="plan-options">
                            @foreach ($bundle->refills as $refill)
                                <div class="plan-option {{ $loop->first ? 'selected' : '' }}"
                                    data-refill-id="{{ $refill->id }}">
                                    <div class="radio-button"></div>
                                    <div class="plan-details">
                                        <span class="plan-data">
                                            {{ $refill->amount_mb >= 1024
                                                ? number_format($refill->amount_mb / 1024, 2) . ' GB'
                                                : $refill->amount_mb . ' MB' }}
                                        </span>
                                        <span class="plan-duration">
                                            {{ \App\Helpers\DateHelper::formatDays($refill->amount_days) }}
                                        </span>
                                    </div>

                                    <span class="plan-price" data-price="{{ $refill->sale_price }}">
                                        {{ $bundle->currency_sign }}{{ number_format($refill->sale_price, 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="plan-actions">
                            <button type="button" class="btn btn-primary checkout-btn" id="checkout-btn">Checkout</button>
                            <button type="button" class="btn btn-outline" onclick="openDeviceCompatibilityPopup()">
                                <i class="fa-solid fa-mobile-screen mr-2"></i>
                                Device Compatibility
                            </button>
                        </div>
                    </div>
                </div>

                <div class="features-tabs">
                    <nav class="tabs-nav">
                        <a href="javascript:void(0)" class="tab-link active" data-tab="features">Key features</a>
                        <a href="javascript:void(0)" class="tab-link" data-tab="description">Description</a>
                        <a href="javascript:void(0)" class="tab-link" data-tab="technical">Technical details</a>
                        <a href="javascript:void(0)" class="tab-link" data-tab="coverage">Coverage & Privacy</a>
                    </nav>

                    <div class="tab-indicator-wrapper">
                        <div class="tab-indicator-bg"></div>
                        <div class="tab-indicator"></div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" data-content="features">
                            <ul>
                                <li><span class="bullet"></span>Get affordable data plans starting at just
                                    {{ $bundle->currency_sign }}{{ number_format($bundle->refills->min('price'), 2) }}.
                                </li>
                                <li><span class="bullet"></span>Enjoy a reliable connection powered by the top networks in
                                    {{ $bundle->name }}.</li>
                                <li><span class="bullet"></span>Compatible with all eSIM-ready smartphones for instant
                                    connectivity.</li>
                            </ul>
                        </div>

                        <div class="tab-pane" data-content="description">
                            <p>
                                {{ $bundle->description ?: 'Kick off your trip with a ' . $bundle->name . ' eSIM and skip the expensive roaming fees! Whether you\'re traveling for leisure or business, a prepaid eSIM keeps you connected across the country. Choose the perfect mobile data plan for your stay — from 1 GB to 20 GB or even unlimited options. Simply download the Saily app, select your plan, and enjoy seamless internet access throughout your visit to ' . $bundle->name . '.' }}
                            </p>
                        </div>

                        <div class="tab-pane" data-content="technical">
                            <ul>
                                <li><span class="bullet"></span>Plan Activation: Automatically activates upon arrival at
                                    your destination — just make sure your Saily eSIM is turned on in your settings and
                                    roaming is enabled.</li>
                                <li><span class="bullet"></span>Plan Duration: Varies by selection — choose between 7-day
                                    or
                                    30-day plans.</li>
                                <li><span class="bullet"></span>Data Plans: Available options range from 1 GB to 20 GB, or
                                    choose an unlimited data plan.</li>
                                <li><span class="bullet"></span>Delivery Time: Instant activation right after purchase.
                                </li>
                                <li><span class="bullet"></span>Speed: Experience 3G, 4G, LTE, or 5G speeds depending on
                                    local network availability.</li>
                                <li><span class="bullet"></span>Hotspot: Fully supported — share your connection without
                                    limits.</li>
                                <li><span class="bullet"></span>Coverage: Get reliable internet across major
                                    {{ $bundle->name }} destinations.*</li>
                            </ul>
                        </div>

                        <div class="tab-pane" data-content="coverage">
                            <div class="space-y-6">
                                <!-- Coverage Information -->
                                <div class="coverage-info">
                                    <h4 class="text-lg font-semibold mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-satellite-dish text-green-500"></i>
                                        Coverage Information
                                    </h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Primary Region:</span>
                                                <span class="font-medium">{{ $region_list[0] ?? 'North America' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Covered Countries:</span>
                                                <span class="font-medium text-right">
                                                    {{ implode(', ', $coverage_list ?? ['Canada', 'United States']) }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Network Type:</span>
                                                <span class="font-medium">4G/LTE, 5G Ready</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Privacy Information -->
                                <div class="privacy-info">
                                    <h4 class="text-lg font-semibold mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-shield-halved text-blue-500"></i>
                                        Privacy & Data Protection
                                    </h4>
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <div class="space-y-2 text-sm">
                                            <div class="flex items-start gap-2">
                                                <i class="fa-solid fa-check text-green-500 mt-1"></i>
                                                <span>Your detected location:
                                                    <strong>{{ $privacy_ip ?? 'Virginia, USA' }}</strong></span>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <i class="fa-solid fa-check text-green-500 mt-1"></i>
                                                <span>IP address used only for regional pricing</span>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <i class="fa-solid fa-check text-green-500 mt-1"></i>
                                                <span>No personal data stored from IP detection</span>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <i class="fa-solid fa-check text-green-500 mt-1"></i>
                                                <span>GDPR and privacy regulation compliant</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-blue-200">
                                            <a href="/privacy-policy"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center gap-1">
                                                Read our complete Privacy Policy
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Tips -->
                                <div class="quick-tips">
                                    <h4 class="text-lg font-semibold mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-lightbulb text-yellow-500"></i>
                                        Quick Tips
                                    </h4>
                                    <div class="grid sm:grid-cols-2 gap-3">
                                        <div class="flex items-center gap-2 p-3 bg-orange-50 rounded-lg">
                                            <i class="fa-solid fa-wifi text-orange-500"></i>
                                            <span class="text-sm">Connect automatically in covered regions</span>
                                        </div>
                                        <div class="flex items-center gap-2 p-3 bg-purple-50 rounded-lg">
                                            <i class="fa-solid fa-bolt text-purple-500"></i>
                                            <span class="text-sm">Instant activation upon arrival</span>
                                        </div>
                                        <div class="flex items-center gap-2 p-3 bg-green-50 rounded-lg">
                                            <i class="fa-solid fa-mobile-screen text-green-500"></i>
                                            <span class="text-sm">Works with all eSIM-compatible devices</span>
                                        </div>
                                        <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-lg">
                                            <i class="fa-solid fa-lock text-blue-500"></i>
                                            <span class="text-sm">Your data and privacy protected</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Extended Coverage Section with Side Roller --}}
    @if (isset($extendedCoverageBundles) && $extendedCoverageBundles->count() > 0)
        <div class="mx-auto" style="background-color: #f6f6f6;">
            <div class="max-w-[1140px] mx-auto py-8 sm:py-12 px-4 sm:px-6">
                <h2 class="text-[20px] sm:text-[20px] font-bold text-[#101010] text-center mb-3 font-['Satoshi']">
                    Need more extensive coverage?
                </h2>
                <p class="text-[16px] sm:text-[16px] text-[#000] text-center mb-8 font-['Satoshi'] max-w-2xl mx-auto">
                    Explore our regional and global eSIMs — prices start as shown and include coverage for
                    {{ $bundle->name }}.
                </p>

                <!-- Side Roller Container -->
                <div class="relative">
                    <!-- Scrollable Container -->
                   <div class="flex overflow-x-auto pb-6 hide-scrollbar gap-4 px-2" id="extended-coverage-roller">
    @foreach ($extendedCoverageBundles as $coverageBundle)
        <div
            class="relative flex-shrink-0 w-[280px] bg-white rounded-[12px] p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 cursor-pointer group"
            onclick="window.location.href='{{ url('/plans/' . $coverageBundle['slug']) }}'">

            <!-- Header with flag and type -->
            <div class="flex items-center gap-3 mb-2">
                <img src="{{ $coverageBundle['image'] }}"
                     alt="{{ $coverageBundle['name'] }}"
                     class="w-8 h-8 object-contain rounded-full">

                <div class="flex-1">
                    <h3 class="text-[16px] font-bold text-[#000000] font-['Satoshi'] ms-2 leading-tight">
                        {{ $coverageBundle['name'] }}
                    </h3>

                    <div class="flex items-center gap-2 mt-5">
                        <span class="text-[12px] font-medium text-[#666]">
                            {{ $coverageBundle['type'] }} eSIM
                        </span>

                        @if ($coverageBundle['is_lifetime'])
                            <span
                                class="text-[10px] px-2 py-[2px] rounded-full bg-[#f4633a]/10 text-[#f4633a] font-semibold">
                                Lifetime
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Price Section -->
            <div class="flex items-center">
                <div>
                    <span class="text-[12px] text-[#828282] font-['Satoshi']">
                        From
                    </span>
                    <span class="plan-price text-[15px] text-[#101010] ml-1"
                          data-price="{{ $coverageBundle['min_price'] }}">
                        {{ $coverageBundle['currency_sign'] }}{{ number_format($coverageBundle['min_price'], 2) }}
                    </span>
                </div>
            </div>

            <!-- Right Chevron (Perfect vertical center) -->
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[#666] group-hover:text-[#f4633a] transition-colors duration-300">
                <i class="fa-solid fa-chevron-right"></i>
            </div>

        </div>
    @endforeach
</div>


                    <!-- Scroll Indicators -->
                    <div class="flex justify-center mt-4 space-x-2">
                        @foreach ($extendedCoverageBundles as $index => $coverageBundle)
                            <div class="w-2 h-2 rounded-full bg-gray-300 indicator-dot {{ $index === 0 ? 'active' : '' }}"
                                data-index="{{ $index }}"></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('partials._how-it-works')

    <a id="faq"></a>
    @include('partials._faq')
    @include('partials._footer')

    <!-- Include Device Compatibility Popup -->
    @if (isset($deviceTypes))
        @include('components.device-compatibility-popup')
    @endif

    @include('components.modals.privacy-ip-modal', [
        'privacy_ip' => $bundle->privacy_ip,
    ])
    @include('components.modals.countries-networks-modal', ['networks' => $networks])
    @include('components.modals.privacy-protected-modal')
    @include('components.modals.region-modal', ['region' => $bundle->region])


    <!-- Checkout Auth Modal -->
    @include('components.checkout-auth-modal')

    <script>
        let selectedRefillId = {{ $bundle->refills->first()->id ?? 'null' }};

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, selectedRefillId:', selectedRefillId);

            // Plan selection functionality
            const planOptions = document.querySelectorAll('.plan-option');

            planOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    planOptions.forEach(opt => opt.classList.remove('selected'));

                    // Add selected class to clicked option
                    this.classList.add('selected');

                    // Update selected refill ID
                    selectedRefillId = this.getAttribute('data-refill-id');
                    console.log('Selected refill ID:', selectedRefillId);

                    // Update session with selected refill
                    updateSelectedRefill();
                });
            });

            // Checkout button click handler
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function() {
                    console.log('Checkout button clicked');
                    handleCheckoutClick();
                });
            }

   
//    key features tab
  const tabsim = document.querySelectorAll('.tab-link');
  const panesim = document.querySelectorAll('.tab-pane');
  const indicatorsim = document.querySelector('.tab-indicator');

  function activateTab(tab) {
    tabsim.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    const target = tab.dataset.tab;
    panesim.forEach(p => {
      p.classList.toggle('active', p.dataset.content === target);
    });

    // Move indicator smoothly
    const rect = tab.getBoundingClientRect();
    const parentRect = tab.parentElement.getBoundingClientRect();
    indicatorsim.style.width = rect.width + 'px';
    indicatorsim.style.left = (rect.left - parentRect.left) + 'px';
  }

  tabsim.forEach(tab => {
    tab.addEventListener('click', e => {
      e.preventDefault();
      activateTab(tab);
    });
  });

  // Set correct indicator position on load
  window.addEventListener('load', () => {
    const active = document.querySelector('.tab-link.active');
    if (active) activateTab(active);
  });

  // Update position on resize
  window.addEventListener('resize', () => {
    const active = document.querySelector('.tab-link.active');
    if (active) activateTab(active);
  });

            // Initialize device compatibility popup if available
            initDeviceCompatibilityPopup();

            // Initialize checkout auth modal
            initCheckoutAuthModal();

            // Initialize extended coverage roller
            initExtendedCoverageRoller();

            // Force remove hidden class from modal on load (in case Rocket.js adds it)
            const modal = document.getElementById('checkout-auth-modal');
            if (modal) {
                modal.classList.add('hidden'); // Ensure it starts hidden
            }
        });

        function updateSelectedRefill() {
            if (!selectedRefillId) {
                console.log('No refill ID selected');
                return;
            }

            console.log('Updating selected refill:', selectedRefillId);

            // Update session with selected refill via AJAX
            fetch('/update-selected-refill', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        refill_id: selectedRefillId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Refill update response:', data);
                })
                .catch(error => {
                    console.error('Error updating refill:', error);
                });
        }

        function handleCheckoutClick() {
            if (!selectedRefillId) {
                alert('Please select a plan first.');
                return;
            }

            console.log('Checking authentication for checkout...');

            // Check if user is authenticated
            fetch('/api/check-auth')
                .then(response => {
                    console.log('Auth response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Auth check data:', data);
                    if (data.authenticated) {
                        // User is authenticated, proceed to checkout
                        console.log('User is authenticated, redirecting to checkout');
                        window.location.href = `/checkout?refill_id=${selectedRefillId}`;
                    } else {
                        // User is not authenticated, show auth modal
                        console.log('User is not authenticated, opening modal');
                        openCheckoutAuthModal();
                    }
                })
                .catch(error => {
                    console.error('Error checking authentication:', error);
                    // Fallback: show auth modal
                    console.log('Error occurred, opening modal as fallback');
                    openCheckoutAuthModal();
                });
        }

       

        // Extended Coverage Roller Functionality
        function initExtendedCoverageRoller() {
            const roller = document.getElementById('extended-coverage-roller');
            const indicators = document.querySelectorAll('.indicator-dot');

            if (roller && indicators.length > 0) {
                roller.addEventListener('scroll', function() {
                    const scrollPosition = roller.scrollLeft;
                    const itemWidth = roller.querySelector('div').offsetWidth + 16; // width + gap
                    const activeIndex = Math.round(scrollPosition / itemWidth);

                    indicators.forEach((dot, index) => {
                        if (index === activeIndex) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                });

                // Click indicators to scroll
                indicators.forEach((dot, index) => {
                    dot.addEventListener('click', function() {
                        const itemWidth = roller.querySelector('div').offsetWidth + 16;
                        roller.scrollTo({
                            left: index * itemWidth,
                            behavior: 'smooth'
                        });
                    });
                });
            }
        }

        // Checkout Auth Modal Functions
        function initCheckoutAuthModal() {
            const modal = document.getElementById('checkout-auth-modal');
            console.log('Initializing modal:', modal);

            if (modal) {
                // Ensure modal starts hidden
                modal.classList.add('hidden');

                // Close modal when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeCheckoutAuthModal();
                    }
                });

                // Close modal with ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeCheckoutAuthModal();
                    }
                });

                // Close button
                const closeBtn = modal.querySelector('.modal-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', closeCheckoutAuthModal);
                }

                // Switch between login and register forms
                const switchToRegister = document.getElementById('switch-to-register');
                const switchToLogin = document.getElementById('switch-to-login');

                if (switchToRegister) {
                    switchToRegister.addEventListener('click', function(e) {
                        e.preventDefault();
                        showRegisterForm();
                    });
                }

                if (switchToLogin) {
                    switchToLogin.addEventListener('click', function(e) {
                        e.preventDefault();
                        showLoginForm();
                    });
                }

                console.log('Modal initialization complete');
            } else {
                console.error('Checkout auth modal not found!');
            }
        }

        function openCheckoutAuthModal() {
            console.log('Opening checkout auth modal');
            const modal = document.getElementById('checkout-auth-modal');

            if (modal) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                modal.style.visibility = 'visible';
                modal.style.opacity = '1';
                modal.style.position = 'fixed';
                modal.style.zIndex = '9999';

                document.body.style.overflow = 'hidden';

                console.log('Modal should be visible now');

                // Show login form by default
                showLoginForm();

                setTimeout(() => {
                    if (modal.classList.contains('hidden') || window.getComputedStyle(modal).display === 'none') {
                        console.warn('Modal still hidden, applying emergency show');
                        emergencyShowModal(modal);
                    }
                }, 100);
            } else {
                console.error('Cannot open modal: checkout-auth-modal not found');
                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
            }
        }

        function emergencyShowModal(modal) {
            modal.style.cssText = `
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                position: fixed !important;
                inset: 0 !important;
                background-color: rgba(0, 0, 0, 0.5) !important;
                z-index: 9999 !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 1rem !important;
            `;
        }

        function closeCheckoutAuthModal() {
            const modal = document.getElementById('checkout-auth-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                modal.style.visibility = 'hidden';
                modal.style.opacity = '0';
                document.body.style.overflow = 'auto';
            }
        }

        function showLoginForm() {
            const loginForm = document.getElementById('checkout-login-form');
            const registerForm = document.getElementById('checkout-register-form');
            const switchToRegister = document.getElementById('switch-to-register');
            const switchToLogin = document.getElementById('switch-to-login');

            if (loginForm && registerForm) {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');

                if (switchToRegister && switchToLogin) {
                    switchToRegister.classList.remove('hidden');
                    switchToLogin.classList.add('hidden');
                }
            }
        }

        function showRegisterForm() {
            const loginForm = document.getElementById('checkout-login-form');
            const registerForm = document.getElementById('checkout-register-form');
            const switchToRegister = document.getElementById('switch-to-register');
            const switchToLogin = document.getElementById('switch-to-login');

            if (loginForm && registerForm) {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');

                if (switchToRegister && switchToLogin) {
                    switchToRegister.classList.add('hidden');
                    switchToLogin.classList.remove('hidden');
                }
            }
        }

        // Handle login form submission
        function handleCheckoutLogin(event) {
            event.preventDefault();
            console.log('Login form submitted');

            const formData = new FormData(event.target);

            fetch('/login', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Login response:', data);
                    if (data.success) {
                        closeCheckoutAuthModal();
                        window.location.href = `/checkout?refill_id=${selectedRefillId}`;
                    } else {
                        alert(data.message || 'Login failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                    alert('An error occurred during login. Please try again.');
                });
        }

        // Handle register form submission
        function handleCheckoutRegister(event) {
            event.preventDefault();
            console.log('Register form submitted');

            const formData = new FormData(event.target);

            fetch('/register', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Register response:', data);
                    if (data.success) {
                        closeCheckoutAuthModal();
                        window.location.href = `/checkout?refill_id=${selectedRefillId}`;
                    } else {
                        alert(data.message || 'Registration failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Registration error:', error);
                    alert('An error occurred during registration. Please try again.');
                });
        }

        // Social login handlers
        function handleCheckoutSocialLogin(provider) {
            console.log('Social login:', provider);

            fetch('/update-selected-refill', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        refill_id: selectedRefillId
                    })
                })
                .then(() => {
                    window.location.href = `/auth/${provider}`;
                })
                .catch(error => {
                    console.error('Error updating refill:', error);
                    window.location.href = `/auth/${provider}`;
                });
        }

        // Device Compatibility Popup Functions
        function initDeviceCompatibilityPopup() {
            const popup = document.getElementById('device-compatibility-popup');
            if (popup) {
                popup.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDeviceCompatibilityPopup();
                    }
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeviceCompatibilityPopup();
                }
            });
        }

        function openDeviceCompatibilityPopup() {
            const popup = document.getElementById('device-compatibility-popup');
            if (popup) {
                popup.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                setTimeout(() => {
                    const searchInput = document.getElementById('popup-device-search');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 300);
            }
        }

        function closeDeviceCompatibilityPopup() {
            const popup = document.getElementById('device-compatibility-popup');
            if (popup) {
                popup.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Make functions globally available
        window.openDeviceCompatibilityPopup = openDeviceCompatibilityPopup;
        window.closeDeviceCompatibilityPopup = closeDeviceCompatibilityPopup;
        window.handleCheckoutLogin = handleCheckoutLogin;
        window.handleCheckoutRegister = handleCheckoutRegister;
        window.handleCheckoutSocialLogin = handleCheckoutSocialLogin;
        window.openCheckoutAuthModal = openCheckoutAuthModal;
        window.closeCheckoutAuthModal = closeCheckoutAuthModal;

        // Test function for debugging
        window.testModal = function() {
            console.log('Testing modal manually');
            openCheckoutAuthModal();
        };

        document.addEventListener('DOMContentLoaded', function() {
    // Clickable cards
    const cards = document.querySelectorAll('[data-modal]');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        });
    });

    // Close modals
    const closeButtons = document.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    });

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            const modal = e.target.closest('.modal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.style.display = 'none';
            });
            document.body.style.overflow = 'auto';
        }
    });
});
    </script>
@endsection

<style>
    /* Info Tips Grid Styles */
    .info-tips-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .info-tip-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #fff;
        border: 1px solid #FFCABB;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

   .info-tip-card:hover {
    background: #fff;
    border-color: #FFCABB;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(244, 99, 58, 0.35); /* #F4633A shadow */
}


    .tip-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 6px;
        background-color: #F6F6F6;
        font-size: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .tip-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .tip-label {
        font-size: 12px;
        font-weight: 400;
        color: #1A1A1A;
        /* text-transform: uppercase; */
        letter-spacing: 0.5px;
    }

    .tip-value {
        font-size: 14px;
        font-weight: 700;
        color: #1A1A1A;
    }

    .tip-note {
        font-size: 10px;
        color: #94a3b8;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .flex.flex-col.justify-start.items-center {
            height: 50vh !important;
            min-height: 400px;
        }

        .hero-section.hero-section {
            padding: 2rem 1rem !important;
            margin-top: 60px !important;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 1.75rem !important;
            line-height: 1.3;
        }

        .breadcrumb {
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Info Tips Mobile */
        .info-tips-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .info-tip-card {
            padding: 10px;
        }

        /* Plans Section Mobile Layout */
        .plans-section .container {
            padding: 0 1rem;
        }

        .plans-title.mb-10 {
            margin-bottom: 2rem !important;
            text-align: center;
        }

        .plans-subtitle {
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .plans-title h2 {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Main Content Mobile Stack */
        .plans-main-content {
            display: flex !important;
            flex-direction: column !important;
            gap: 2rem;
        }

        .plans-image {
            width: 100% !important;
            order: 2;
        }

        .plans-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
        }

        .plans-selector {
            width: 100% !important;
            order: 1;
        }

        .plans-selector h3 {
            font-size: 1.25rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .plans-selector p {
            text-align: center;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        /* Plan Options Mobile Grid */
        .plan-options-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .plan-option {
            padding: 1rem;
            display: flex !important;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .plan-option.selected {
            border-color: #000000;
            background-color: #f8fafc;
        }

        .plan-details {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .plan-data {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .plan-duration {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .plan-price {
            font-size: 1.125rem;
            font-weight: 600;
            color: #000000;
        }

        /* Plan Actions Mobile Stack */
        .plan-actions {
            display: flex !important;
            flex-direction: column;
            gap: 1rem;
        }

        .plan-actions .btn {
            width: 100%;
            justify-content: center;
            padding: 0.875rem 1rem;
            font-size: 0.9rem;
        }

        /* Features Tabs Mobile */
        .features-tabs {
            margin-top: 2rem;
        }

        .tabs-nav {
            display: flex !important;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .tab-link {
            padding: 0.75rem 1rem;
            text-align: center;
            border-radius: 8px;
            border: 1px solid white;
            font-size: 0.9rem;
        }

        .tab-link.active {
            background-color: white;
            color: white;
            border-color: white;
        }

        .tab-indicator-wrapper {
            display: none;
        }

        .tab-content {
            padding: 0;
        }

        .tab-pane ul {
            padding-left: 0;
        }

        .tab-pane ul li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .bullet {
            min-width: 6px;
            height: 6px;
            background-color: #ececec;
            border-radius: 50%;
            margin-top: 0.5rem;
        }

        .tab-pane p {
            font-size: 0.9rem;
            line-height: 1.6;
            text-align: left;
        }

        /* Coverage Tab Mobile */
        .quick-tips .grid {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 480px) {
        .hero-section.hero-section {
            margin-top: 50px !important;
            padding: 1.5rem 1rem !important;
        }

        .hero-content h1 {
            font-size: 1.5rem !important;
        }

        .plans-title h2 {
            font-size: 1.25rem;
        }

        .plans-selector h3 {
            font-size: 1.125rem;
        }

        .plan-option {
            padding: 0.875rem;
        }

        .plan-data {
            font-size: 0.9rem;
        }

        .plan-price {
            font-size: 1rem;
        }

        .plan-actions .btn {
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
        }

        /* Extended coverage roller mobile */
        #extended-coverage-roller>div {
            width: 260px;
        }
    }

    /* Ensure radio buttons are visible on mobile */
    .radio-button {
        /* min-width: 20px; */
        /* height: 20px; */
        border: 2px solid #d1d5db;
        border-radius: 50%;
        margin-right: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .plan-option.selected .radio-button {
        border-color: white;
    }

    .plan-option.selected .radio-button::after {
        content: '';
        width: 10px;
        height: 10px;
        background-color: white;
        border-radius: 50%;
    }

    /* Extended Coverage Roller Styles */
    .hide-scrollbar {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari and Opera */
    }

    #extended-coverage-roller {
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
          -webkit-overflow-scrolling: touch;
    }
    .hide-scrollbar {
  scrollbar-width: none; /* Firefox */
}
.hide-scrollbar::-webkit-scrollbar {
  display: none; /* Chrome, Safari */
}

    #extended-coverage-roller>div {
        scroll-snap-align: start;
    }

    .indicator-dot.active {
        background-color: #f4633a;
    }

    /* Checkout Auth Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 1rem;
    }

    .modal-overlay.hidden {
        display: none;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        width: 100%;
        max-width: 400px;
        position: relative;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
    }

    .modal-close:hover {
        color: #374151;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
    }

    .modal-subtitle {
        text-align: center;
        color: #6b7280;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .social-login-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .social-btn:hover {
        background-color: #f9fafb;
    }

    .social-btn.google {
        border-color: #db4437;
        color: #db4437;
    }

    .social-btn.facebook {
        border-color: #4267B2;
        color: #4267B2;
    }

    .social-btn.apple {
        border-color: #000000;
        color: #000000;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background-color: #e5e7eb;
    }

    .divider::before {
        margin-right: 1rem;
    }

    .divider::after {
        margin-left: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.9rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #000000;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }

    .submit-btn {
        width: 100%;
        padding: 0.75rem;
        background-color: #000000;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .submit-btn:hover {
        background-color: #333333;
    }

    .form-switch {
        text-align: center;
        margin-top: 1rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .form-switch a {
        color: #000000;
        text-decoration: none;
        font-weight: 500;
    }

    .form-switch a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .modal-content {
            padding: 1.5rem;
            margin: 1rem;
        }

        .modal-title {
            font-size: 1.25rem;
        }

        .social-btn {
            font-size: 0.85rem;
            padding: 0.65rem 0.75rem;
        }
    }
</style>
