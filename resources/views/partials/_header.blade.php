<header class="fixed top-0 w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-[20px] z-50" id="1:909">
    <div class="flex justify-between items-center w-full bg-[#ffffff] rounded-lg px-4 sm:px-5 lg:px-[20px] py-3 sm:py-4 lg:py-[16px] shadow-[0px_1px_5px_#00000014]">

        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/img_frame.svg') }}" alt="Esimira Logo" class="w-[80px] sm:w-[96px] lg:w-[112px] h-auto" id="1:910">
            </a>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-6" id="1_38_481_44_286_24">
            @php
                $nav = [
                    ['route' => 'home', 'label' => 'Home'],
                    ['route' => 'why-choose-esimira', 'label' => 'Why Choose Esimira'],
                    ['route' => 'plans', 'label' => 'Our eSIM Plans'],
                    ['route' => 'about', 'label' => 'About Esimira'],
                    ['route' => 'help', 'label' => 'Help'],
                    ['route' => 'reseller-business', 'label' => 'Reseller & Business'],
                    ['route' => 'my-esims', 'label' => 'My Esims'],
                ];
            @endphp

            @foreach ($nav as $item)
                @if ($item['route'] === 'my-esims' && !auth()->check())
                    @continue
                @endif
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp
                <a href="{{ route($item['route']) }}"
                    class="text-[16px] {{ $isActive ? 'font-bold text-[#f4633a]' : 'font-normal text-[#101010]' }} font-['Satoshi'] leading-[22px] hover:text-[#f4633a] transition-colors"
                    id="{{ $loop->iteration == 1 ? '1:953' : ($loop->iteration == 2 ? '1:954' : ($loop->iteration == 3 ? '1:955' : '1:956')) }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Button -->
            <button id="mobileMenuButton" class="lg:hidden p-2 rounded-md text-[#101010] hover:bg-gray-100" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @auth
           <!-- MiraVault Balance -->
            <div class="hidden lg:flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg border border-gray-200" 
                id="miravaultBalanceContainer">
                <span class="text-sm font-semibold text-[#f4633a] font-['Satoshi']">
                    {{ getCurrencySymbol() }}<span id="miravaultBalance">{{ number_format(Auth::user()->wallet_balance ?? Auth::user()->balance ?? 0, 2) }}</span>
                </span>
            </div>
            @endauth

            <!-- Currency Selector - Hidden on mobile -->
            <div class="hidden lg:block relative" id="currencySelector">
                @php
                    $currencyData = getCurrencyData();
                    $currentCurrency = getCurrentCurrency();
                    $currentSymbol = getCurrencySymbol();
                    $exchangeRates = getExchangeRates();
                @endphp

                <!-- Visible custom dropdown -->
                <div class="flex items-center gap-2 cursor-pointer group px-3 py-2 rounded-lg border border-gray-200 hover:border-[#f4633a] transition-colors" id="currencyDropdownButton">
                    <span class="text-sm font-medium text-gray-900 font-['Satoshi']">
                        {{ $currentCurrency }}
                    </span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <!-- Dropdown menu -->
                <div id="currencyDropdownMenu" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900 font-['Satoshi']">Select Currency</h3>
                    </div>
                    <ul class="py-2 max-h-60 overflow-y-auto" id="currencyList">
                        @foreach ($currencyData as $code => $currency)
                            <li class="border-b border-gray-100 last:border-b-0">
                                <a href="#" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 currency-option {{ $currentCurrency === $code ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}" data-currency="{{ $code }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ $code }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">{{ $currency['name'] }}</span>
                                            <span class="text-xs text-gray-500">{{ $currency['symbol'] }}</span>
                                        </div>
                                    </div>
                                    @if($currentCurrency === $code)
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Language Selector - Hidden on mobile -->
            <div class="hidden lg:block relative" id="languageSelector">
                <!-- Visible custom dropdown -->
                <div class="flex items-center gap-2 cursor-pointer group px-3 py-2 rounded-lg border border-gray-200 hover:border-[#f4633a] transition-colors" id="languageDropdownButton">
                    <span class="text-sm font-medium text-gray-900 font-['Satoshi'] uppercase">
                        {{ strtoupper(app()->getLocale()) }}
                    </span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <!-- Dropdown menu -->
                <div id="languageDropdownMenu" class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-20">
                    <ul class="py-1 text-sm text-gray-900 font-['Satoshi']">
                        @foreach (['en' => 'English', 'ar' => 'العربية'] as $code => $name)
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-50 language-option {{ app()->getLocale() === $code ? 'bg-gray-50 font-medium text-[#f4633a]' : '' }}" data-lang="{{ $code }}">
                                    {{ $name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Auth Section -->
            @auth
                <!-- Profile Navigation (visible when logged in) -->
                <div class="profile-nav hidden md:flex items-center" id="profileNav">
                    <div class="profile-info flex items-center gap-3 cursor-pointer relative bg-gray-50 rounded-lg px-3 py-2 border border-gray-200 hover:border-[#f4633a] transition-colors">
                        
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-[#101010] leading-none">{{ Auth::user()->name }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        <!-- Dropdown Menu -->
                        <div class="dropdown-menu absolute top-full right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50 overflow-hidden" id="profileDropdown">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-sm font-semibold text-gray-900 font-['Satoshi']">My Account</h3>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-900 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Dashboard
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-900 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile Settings
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-900 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    Billing & Payments
                                </a>
                                <div class="border-t border-gray-100 mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Auth Buttons (visible when not logged in) -->
                <div class="auth-buttons hidden md:flex items-center gap-3 sm:gap-5" id="authButtons">
                    <a href="{{ route('register') }}" class="text-[14px] sm:text-[16px] font-normal text-[#f4633a] font-['Satoshi'] leading-[22px] uppercase border border-[#f4633a] rounded-[18px] px-4 sm:px-6 py-2 bg-[#ffffff] hover:bg-[#f4633a] hover:text-white transition-all">Sign Up</a>
                    <a href="{{ route('login') }}" class="text-[14px] sm:text-[16px] font-normal text-[#ffffff] font-['Satoshi'] leading-[22px] uppercase rounded-[18px] px-4 sm:px-6 py-2 bg-[#f4633a] hover:bg-[#e55a33] transition-all">Sign In</a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Mobile Navigation -->
<div class="lg:hidden">
    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-30 z-40 hidden transition-opacity duration-300"></div>

    <!-- Mobile Menu -->
    <nav id="mobileMenu" class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg z-50 p-6 overflow-y-auto transform translate-x-full transition-transform duration-300">
        <div class="flex justify-between items-center mb-8">
            <img src="{{ asset('assets/images/img_frame.svg') }}" alt="Logo" class="w-24 h-auto">
            <button id="closeMobileMenu" class="p-2 hover:bg-gray-100 rounded-md transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        @auth
            <!-- Mobile MiraVault Balance -->
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg mb-6">
                <div class="w-10 h-10 bg-[#f4633a] rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">MiraVault Balance</p>
                    <p class="text-lg font-semibold text-[#f4633a]" id="mobileMiravaultBalance">
                        {{ getCurrencySymbol() }}{{ number_format(Auth::user()->balance ?? 0, 2) }}
                    </p>
                </div>
            </div>
        @endauth

        <!-- Mobile Navigation Links -->
        <div class="flex flex-col gap-3 mb-6">
            @foreach ($nav as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" class="flex items-center gap-3 text-base {{ $isActive ? 'font-bold text-[#f4633a] bg-orange-50' : 'font-normal text-[#101010]' }} hover:text-[#f4633a] hover:bg-gray-50 transition-colors mobile-nav-link py-3 px-4 rounded-lg">
                    <!-- Universal navigation icons -->
                    @if($item['route'] === 'home')
                        <!-- Home icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    @elseif($item['route'] === 'why-choose-esimira')
                        <!-- Checkmark/benefits icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($item['route'] === 'plans')
                        <!-- Plans/package icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    @elseif($item['route'] === 'about')
                        <!-- Information icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($item['route'] === 'help')
                        <!-- Help/support icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <!-- Default navigation icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    @endif
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <!-- Mobile Settings Section -->
        <div class="space-y-4 mb-6">
            <!-- Currency Dropdown -->
            <div class="bg-gray-50 rounded-lg overflow-hidden">
                <button class="mobile-dropdown-toggle w-full flex items-center justify-between p-4 text-left hover:bg-gray-100 transition-colors" data-target="currencyDropdown">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-900">Currency</span>
                            <span class="block text-xs text-gray-500" id="mobileCurrentCurrency">{{ $currentCurrency }} - {{ $currencyData[$currentCurrency]['name'] }}</span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="currencyDropdown" class="mobile-dropdown-content hidden border-t border-gray-200">
                    <div class="p-4 space-y-2 max-h-60 overflow-y-auto">
                        @foreach ($currencyData as $code => $currency)
                            <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer mobile-currency-option {{ $currentCurrency === $code ? 'border-blue-500 bg-blue-50' : '' }}" data-currency="{{ $code }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ $code }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">{{ $currency['name'] }}</span>
                                        <span class="text-xs text-gray-500">{{ $currency['symbol'] }}</span>
                                    </div>
                                </div>
                                @if($currentCurrency === $code)
                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Language Dropdown -->
            <div class="bg-gray-50 rounded-lg overflow-hidden">
                <button class="mobile-dropdown-toggle w-full flex items-center justify-between p-4 text-left hover:bg-gray-100 transition-colors" data-target="languageDropdown">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-900">Language</span>
                            <span class="block text-xs text-gray-500" id="mobileCurrentLanguage">
                                @if(app()->getLocale() === 'en') English @else العربية @endif
                            </span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="languageDropdown" class="mobile-dropdown-content hidden border-t border-gray-200">
                    <div class="p-4 space-y-2">
                        @foreach (['en' => 'English', 'ar' => 'العربية'] as $code => $name)
                            <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-[#f4633a] hover:bg-orange-50 transition-all cursor-pointer mobile-language-option {{ app()->getLocale() === $code ? 'border-[#f4633a] bg-orange-50' : '' }}" data-lang="{{ $code }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700 uppercase">{{ $code }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $name }}</span>
                                </div>
                                @if(app()->getLocale() === $code)
                                    <svg class="w-5 h-5 text-[#f4633a]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Auth Section -->
        <div class="mt-8 border-t border-gray-200 pt-6">
            @guest
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('register') }}" class="text-center py-3 border border-[#f4633a] text-[#f4633a] rounded-lg hover:bg-[#f4633a] hover:text-white transition-all font-medium text-sm">Sign Up</a>
                    <a href="{{ route('login') }}" class="text-center py-3 bg-[#f4633a] text-white rounded-lg hover:bg-[#e55a33] transition-all font-medium text-sm">Sign In</a>
                </div>
            @else
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-[#f4633a] rounded-full flex items-center justify-center">
                            <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-[#101010] text-sm">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 w-full text-center py-3 text-[#101010] hover:text-[#f4633a] transition-all font-medium border border-gray-200 rounded-lg justify-center text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full justify-center py-3 border border-[#f4633a] text-[#f4633a] rounded-lg hover:bg-[#f4633a] hover:text-white transition-all font-medium text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </nav>
</div>
</header>

<!-- Global Price Display Elements -->
<div id="globalCurrencyDisplay" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg border border-gray-200 p-4 z-40 hidden">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-[#f4633a] rounded-full flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-900">Currency Updated</p>
            <p class="text-xs text-gray-500">All prices are now in <span id="currentCurrencyDisplay"></span></p>
        </div>
    </div>
</div>

<!-- Global Currency Configuration -->
<script>
window.CurrencyConfig = {
    // Current currency from session
    currentCurrency: '{{ $currentCurrency }}',
    
    // Exchange rates from database (will be populated dynamically)
    exchangeRates: @json($exchangeRates),
    
    // Currency data with symbols and names
    currencyData: @json($currencyData)
};

// Global Currency Helper Functions
window.CurrencyHelper = {
    // Initialize with global config
    currentCurrency: window.CurrencyConfig.currentCurrency,
    exchangeRates: window.CurrencyConfig.exchangeRates,
    currencyData: window.CurrencyConfig.currencyData,

    // Initialize currency system
    init: async function() {
        // First, check localStorage for currency preference
        const storedCurrency = localStorage.getItem('selectedCurrency');
        if (storedCurrency && this.currencyData[storedCurrency]) {
            // If stored currency is different from current, switch to it
            if (storedCurrency !== this.currentCurrency) {
                await this.setCurrency(storedCurrency);
                return; // setCurrency will handle the rest
            }
        }
        
        await this.loadExchangeRates();
        this.updateAllPrices();
        this.updateCurrencyDisplay();
        this.highlightSelectedCurrency();
        this.updateMobileCurrencyDisplay();
        this.updateMiravaultBalance();
    },

    // Load exchange rates from backend
    loadExchangeRates: async function() {
        try {
            const response = await fetch("{{ route('currency.rates') }}");
            const data = await response.json();
            
            if (data.success) {
                this.exchangeRates = data.rates;
                this.currentCurrency = data.current_currency;
                
                // Update global config
                window.CurrencyConfig.exchangeRates = data.rates;
                window.CurrencyConfig.currentCurrency = data.current_currency;
                
                this.populateCurrencyDropdowns();
                this.updateMobileCurrencyDisplay();
                this.updateMiravaultBalance();
            }
        } catch (error) {
            console.error('Error loading exchange rates:', error);
            // Use default rates from global config
            this.exchangeRates = window.CurrencyConfig.exchangeRates;
        }
    },

    // Convert price from USD to target currency
    convertPrice: function(usdPrice, targetCurrency = this.currentCurrency) {
        if (!this.exchangeRates[targetCurrency]) {
            console.warn(`Exchange rate for ${targetCurrency} not found`);
            return usdPrice;
        }
        
        const converted = usdPrice * this.exchangeRates[targetCurrency];
        return Math.round(converted * 100) / 100;
    },

    // Format price with currency symbol
    formatPrice: function(usdPrice, targetCurrency = this.currentCurrency, format = 'standard') {
        const convertedPrice = this.convertPrice(usdPrice, targetCurrency);
        const symbol = this.currencyData[targetCurrency]?.symbol || targetCurrency;
        
        if (format === 'compact') {
            return `${symbol}${convertedPrice.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
        } else {
            const formattedPrice = convertedPrice % 1 === 0 ? 
                convertedPrice.toFixed(0) : convertedPrice.toFixed(2);
            return `${symbol}${formattedPrice}`;
        }
    },

    // Update all prices on the page
    updateAllPrices: function() {
        console.log('Updating prices for currency:', this.currentCurrency);
        
        // Update elements with data-price attribute
        document.querySelectorAll('[data-price]').forEach(element => {
            const usdPrice = parseFloat(element.getAttribute('data-price'));
            const format = element.getAttribute('data-format') || 'standard';
            const convertedPrice = this.formatPrice(usdPrice, this.currentCurrency, format);
            element.textContent = convertedPrice;
        });

        // Update elements with data-price-usd attribute
        document.querySelectorAll('[data-price-usd]').forEach(element => {
            const usdPrice = parseFloat(element.getAttribute('data-price-usd'));
            const format = element.getAttribute('data-format') || 'standard';
            const convertedPrice = this.formatPrice(usdPrice, this.currentCurrency, format);
            element.textContent = convertedPrice;
        });

        // Update custom price elements
        document.querySelectorAll('.price-element').forEach(element => {
            const usdPrice = parseFloat(element.getAttribute('data-usd-price'));
            if (!isNaN(usdPrice)) {
                const convertedPrice = this.formatPrice(usdPrice, this.currentCurrency);
                element.textContent = convertedPrice;
            }
        });

        // Update any elements with currency display
        document.querySelectorAll('[data-currency-display]').forEach(element => {
            element.textContent = this.currentCurrency;
        });

        // Update Miravault balance
        this.updateMiravaultBalance();
    },

    // Set new currency and update everything
    setCurrency: async function(currencyCode) {
        try {
            const response = await fetch("{{ route('currency.switch') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ currency: currencyCode })
            });

            const data = await response.json();
            
            if (data.success) {
                this.currentCurrency = currencyCode;
                this.exchangeRates = data.rates;
                
                // Update global config
                window.CurrencyConfig.currentCurrency = currencyCode;
                window.CurrencyConfig.exchangeRates = data.rates;
                
                this.updateAllPrices();
                this.updateCurrencyDisplay();
                this.highlightSelectedCurrency();
                this.updateMobileCurrencyDisplay();
                this.updateMiravaultBalance();
                this.showNotification(currencyCode);
                
                // Store in localStorage for persistence
                localStorage.setItem('selectedCurrency', currencyCode);
                
                return true;
            }
        } catch (error) {
            console.error('Error switching currency:', error);
        }
        return false;
    },

    // Update Miravault balance display
    updateMiravaultBalance: function() {
        const balanceElement = document.getElementById('miravaultBalance');
        const mobileBalanceElement = document.getElementById('mobileMiravaultBalance');
        
        // Get the user's balance in USD (assuming it's stored in USD)
        const usdBalance = {{ Auth::user()->balance ?? 0 }};
        
        // Convert to current currency
        const convertedBalance = this.convertPrice(usdBalance, this.currentCurrency);
        const symbol = this.currencyData[this.currentCurrency]?.symbol || '$';
        const formattedBalance = `${symbol}${convertedBalance.toFixed(2)}`;
        
        if (balanceElement) {
            balanceElement.textContent = formattedBalance;
        }
        
        if (mobileBalanceElement) {
            mobileBalanceElement.textContent = formattedBalance;
        }
    },

    // Update mobile currency display
    updateMobileCurrencyDisplay: function() {
        const currentCurrencyDisplay = document.getElementById('mobileCurrentCurrency');
        if (currentCurrencyDisplay && this.currencyData[this.currentCurrency]) {
            currentCurrencyDisplay.textContent = `${this.currentCurrency} - ${this.currencyData[this.currentCurrency].name}`;
        }
    },

    // Get current currency symbol
    getCurrentSymbol: function() {
        return this.currencyData[this.currentCurrency]?.symbol || '$';
    },

    // Get current currency code
    getCurrentCurrency: function() {
        return this.currentCurrency;
    },

    // Get exchange rate for a specific currency
    getExchangeRate: function(currency) {
        return this.exchangeRates[currency] || 1;
    },

    // Get all available currencies
    getAvailableCurrencies: function() {
        return Object.keys(this.currencyData);
    },

    // Helper methods for dropdown
    updateCurrencyDisplay: function() {
        const currencyButton = document.querySelector('#currencyDropdownButton span');
        if (currencyButton) {
            currencyButton.textContent = this.currentCurrency;
        }
    },

    // Highlight selected currency in dropdowns
    highlightSelectedCurrency: function() {
        // Update desktop dropdown
        const currencyOptions = document.querySelectorAll('.currency-option');
        currencyOptions.forEach(option => {
            const currency = option.getAttribute('data-currency');
            if (currency === this.currentCurrency) {
                option.classList.add('bg-blue-50', 'border-r-2', 'border-blue-500');
                const checkIcon = option.querySelector('svg');
                if (checkIcon) checkIcon.classList.remove('hidden');
            } else {
                option.classList.remove('bg-blue-50', 'border-r-2', 'border-blue-500');
                const checkIcon = option.querySelector('svg');
                if (checkIcon) checkIcon.classList.add('hidden');
            }
        });

        // Update mobile dropdown
        const mobileCurrencyOptions = document.querySelectorAll('.mobile-currency-option');
        mobileCurrencyOptions.forEach(option => {
            const currency = option.getAttribute('data-currency');
            if (currency === this.currentCurrency) {
                option.classList.add('border-blue-500', 'bg-blue-50');
                const checkIcon = option.querySelector('svg');
                if (checkIcon) checkIcon.classList.remove('hidden');
            } else {
                option.classList.remove('border-blue-500', 'bg-blue-50');
                const checkIcon = option.querySelector('svg');
                if (checkIcon) checkIcon.classList.add('hidden');
            }
        });

        // Update currency button text
        this.updateCurrencyDisplay();
    },

    populateCurrencyDropdowns: function() {
        const currencyList = document.getElementById('currencyList');
        const mobileCurrencyList = document.getElementById('mobileCurrencyList');
        
        if (currencyList) {
            currencyList.innerHTML = Object.keys(this.currencyData).map(code => `
                <li class="border-b border-gray-100 last:border-b-0">
                    <a href="#" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 currency-option ${this.currentCurrency === code ? 'bg-blue-50 border-r-2 border-blue-500' : ''}" data-currency="${code}">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-700">${code}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">${this.currencyData[code].name}</span>
                                <span class="text-xs text-gray-500">${this.currencyData[code].symbol}</span>
                            </div>
                        </div>
                        ${this.currentCurrency === code ? `
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        ` : ''}
                    </a>
                </li>
            `).join('');
        }

        if (mobileCurrencyList) {
            mobileCurrencyList.innerHTML = Object.keys(this.currencyData).map(code => `
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer mobile-currency-option ${this.currentCurrency === code ? 'border-blue-500 bg-blue-50' : ''}" data-currency="${code}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">${code}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">${this.currencyData[code].name}</span>
                            <span class="text-xs text-gray-500">${this.currencyData[code].symbol}</span>
                        </div>
                    </div>
                    ${this.currentCurrency === code ? `
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    ` : ''}
                </div>
            `).join('');
        }
    },

    showNotification: function(currencyCode) {
        const display = document.getElementById('globalCurrencyDisplay');
        const currentDisplay = document.getElementById('currentCurrencyDisplay');
        
        if (display && currentDisplay) {
            currentDisplay.textContent = `${currencyCode} (${this.currencyData[currencyCode]?.symbol})`;
            display.classList.remove('hidden');
            
            setTimeout(() => {
                display.classList.add('hidden');
            }, 3000);
        }
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    window.CurrencyHelper.init();
});
</script>

<!-- Main JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize currency system first
    window.CurrencyHelper.init();

    // Mobile Menu Elements
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

    // Language Elements
    const languageDropdownButton = document.getElementById('languageDropdownButton');
    const languageDropdownMenu = document.getElementById('languageDropdownMenu');
    const languageOptions = document.querySelectorAll('.language-option');
    const mobileLanguageOptions = document.querySelectorAll('.mobile-language-option');

    // Currency Elements
    const currencyDropdownButton = document.getElementById('currencyDropdownButton');
    const currencyDropdownMenu = document.getElementById('currencyDropdownMenu');

    // Profile Navigation Elements
    const profileNav = document.getElementById('profileNav');
    const profileDropdown = document.getElementById('profileDropdown');

    // Mobile Menu Functions
    function openMobileMenu() {
        mobileMenuOverlay.classList.remove('hidden');
        mobileMenu.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenuFunc() {
        mobileMenuOverlay.classList.add('hidden');
        mobileMenu.classList.add('translate-x-full');
        document.body.style.overflow = '';
    }

    // Language Functions
    function switchLanguage(lang) {
        // Get current URL and path
        const currentUrl = window.location.href;
        const currentPath = window.location.pathname;
        
        // Check if the URL already contains a language segment
        const pathSegments = currentPath.split('/').filter(segment => segment);
        
        // Remove existing language segment if present
        let newPathSegments = pathSegments.filter(segment => 
            !['en', 'ar'].includes(segment)
        );
        
        // Add new language as first segment
        newPathSegments.unshift(lang);
        
        // Build new URL
        const newPath = '/' + newPathSegments.join('/');
        const newUrl = window.location.origin + newPath + window.location.search + window.location.hash;
        
        // Use AJAX to set session and then redirect
        fetch("{{ route('language.switch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ lang: lang })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to new URL with language segment
                window.location.href = newUrl;
            } else {
                console.error('Language switch failed');
            }
        })
        .catch(error => {
            console.error('Error switching language:', error);
            // Fallback: redirect anyway
            window.location.href = newUrl;
        });
    }

    function toggleLanguageDropdown() {
        languageDropdownMenu.classList.toggle('hidden');
    }

    function closeLanguageDropdown() {
        languageDropdownMenu.classList.add('hidden');
    }

    // Currency Functions
    function toggleCurrencyDropdown() {
        currencyDropdownMenu.classList.toggle('hidden');
    }

    function closeCurrencyDropdown() {
        currencyDropdownMenu.classList.add('hidden');
    }

    // Profile Dropdown Functions
    function toggleProfileDropdown() {
        if (profileDropdown) {
            profileDropdown.classList.toggle('hidden');
        }
    }

    function closeProfileDropdown() {
        if (profileDropdown) {
            profileDropdown.classList.add('hidden');
        }
    }

    // Mobile Dropdown Functionality
    function initMobileDropdowns() {
        // Toggle dropdowns
        const dropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
        
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const target = document.getElementById(targetId);
                const icon = this.querySelector('svg');
                
                // Toggle current dropdown
                target.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
                
                // Close other dropdowns
                dropdownToggles.forEach(otherToggle => {
                    if (otherToggle !== this) {
                        const otherTargetId = otherToggle.getAttribute('data-target');
                        const otherTarget = document.getElementById(otherTargetId);
                        const otherIcon = otherToggle.querySelector('svg');
                        
                        otherTarget.classList.add('hidden');
                        otherIcon.classList.remove('rotate-180');
                    }
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.mobile-dropdown-toggle') && !event.target.closest('.mobile-dropdown-content')) {
                dropdownToggles.forEach(toggle => {
                    const targetId = toggle.getAttribute('data-target');
                    const target = document.getElementById(targetId);
                    const icon = toggle.querySelector('svg');
                    
                    target.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                });
            }
        });

        // Currency selection
        document.addEventListener('click', function(e) {
            if (e.target.closest('.mobile-currency-option')) {
                e.preventDefault();
                const currencyOption = e.target.closest('.mobile-currency-option');
                const currency = currencyOption.getAttribute('data-currency');
                
                window.CurrencyHelper.setCurrency(currency).then(success => {
                    if (success) {
                        // Close currency dropdown
                        const currencyDropdown = document.getElementById('currencyDropdown');
                        const currencyToggle = document.querySelector('[data-target="currencyDropdown"]');
                        const currencyIcon = currencyToggle.querySelector('svg');
                        
                        currencyDropdown.classList.add('hidden');
                        currencyIcon.classList.remove('rotate-180');
                        
                        // Update all UI elements
                        window.CurrencyHelper.highlightSelectedCurrency();
                    }
                });
            }
        });

        // Language selection
        document.addEventListener('click', function(e) {
            if (e.target.closest('.mobile-language-option')) {
                e.preventDefault();
                const languageOption = e.target.closest('.mobile-language-option');
                const lang = languageOption.getAttribute('data-lang');
                
                // Update current language display immediately for better UX
                const currentLanguageDisplay = document.getElementById('mobileCurrentLanguage');
                if (currentLanguageDisplay) {
                    currentLanguageDisplay.textContent = lang === 'en' ? 'English' : 'العربية';
                }
                
                // Close language dropdown
                const languageDropdown = document.getElementById('languageDropdown');
                const languageToggle = document.querySelector('[data-target="languageDropdown"]');
                const languageIcon = languageToggle.querySelector('svg');
                
                languageDropdown.classList.add('hidden');
                languageIcon.classList.remove('rotate-180');
                
                // Switch language
                switchLanguage(lang);
            }
        });
    }

    // Initialize mobile dropdowns
    initMobileDropdowns();

    // Event Listeners
    if (mobileMenuButton) mobileMenuButton.addEventListener('click', openMobileMenu);
    if (closeMobileMenu) closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
    if (mobileMenuOverlay) mobileMenuOverlay.addEventListener('click', closeMobileMenuFunc);

    // Mobile nav links
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenuFunc);
    });

    // Language functionality
    if (languageDropdownButton) {
        languageDropdownButton.addEventListener('click', toggleLanguageDropdown);
    }

    languageOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');
            switchLanguage(lang);
            closeLanguageDropdown();
        });
    });

    // Currency functionality
    if (currencyDropdownButton) {
        currencyDropdownButton.addEventListener('click', toggleCurrencyDropdown);
    }

    // Currency option clicks (delegated)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.currency-option')) {
            e.preventDefault();
            const currency = e.target.closest('.currency-option').getAttribute('data-currency');
            window.CurrencyHelper.setCurrency(currency).then(success => {
                if (success) {
                    closeCurrencyDropdown();
                    window.CurrencyHelper.highlightSelectedCurrency();
                }
            });
        }
    });

    // Profile dropdown
    if (profileNav) {
        const profileInfo = profileNav.querySelector('.profile-info');
        if (profileInfo) {
            profileInfo.addEventListener('click', toggleProfileDropdown);
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('#languageSelector')) {
            closeLanguageDropdown();
        }
        if (!event.target.closest('#currencySelector')) {
            closeCurrencyDropdown();
        }
        if (!event.target.closest('#profileNav')) {
            closeProfileDropdown();
        }
    });

    // Close on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeMobileMenuFunc();
            closeLanguageDropdown();
            closeCurrencyDropdown();
            closeProfileDropdown();
        }
    });

    // Prevent body scroll when mobile menu is open
    function preventBodyScroll(prevent) {
        if (prevent) {
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
        }
    }

    // Enhanced mobile menu open/close with scroll prevention
    const originalOpenMobileMenu = openMobileMenu;
    const originalCloseMobileMenuFunc = closeMobileMenuFunc;

    openMobileMenu = function() {
        originalOpenMobileMenu();
        preventBodyScroll(true);
    };

    closeMobileMenuFunc = function() {
        originalCloseMobileMenuFunc();
        preventBodyScroll(false);
    };
});
</script>

<style>
/* Currency selection styles */
.currency-option.bg-blue-50 {
    background-color: #eff6ff !important;
    border-right: 2px solid #f4633a !important;
}

.mobile-currency-option.border-blue-500 {
    border-color: #f4633a !important;
    background-color: #eff6ff !important;
}

/* Smooth transitions for currency changes */
[data-price], [data-price-usd], .price-element {
    transition: all 0.3s ease;
}

/* Mobile Dropdown Styles */
.mobile-dropdown-toggle {
    transition: all 0.3s ease;
}

.mobile-dropdown-toggle:hover {
    background-color: #f9fafb;
}

.mobile-dropdown-content {
    transition: all 0.3s ease;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.rotate-180 {
    transform: rotate(180deg);
}

/* Mobile option hover effects */
.mobile-currency-option,
.mobile-language-option {
    transition: all 0.2s ease;
}

.mobile-currency-option:hover,
.mobile-language-option:hover {
    transform: translateX(4px);
}

/* Mobile menu scrollbar styling */
#mobileMenu::-webkit-scrollbar {
    width: 4px;
}

#mobileMenu::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#mobileMenu::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

#mobileMenu::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Enhanced profile dropdown styling */
.profile-info {
    transition: all 0.3s ease;
}

.profile-info:hover {
    border-color: #f4633a !important;
}

.dropdown-menu {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Currency and language selector enhancements */
#currencyDropdownButton:hover,
#languageDropdownButton:hover {
    border-color: #f4633a !important;
}

/* MiraVault balance styling */
#miravaultBalance {
    transition: all 0.3s ease;
}

/* Mobile view enhancements */
.mobile-view {
    /* Add any mobile-specific styles here */
}

/* Prevent horizontal scroll on mobile */
body.mobile-view {
    overflow-x: hidden;
}
</style>