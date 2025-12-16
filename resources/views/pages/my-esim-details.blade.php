@extends('layouts.app')

@section('title', __('eSIM Details – Esimira'))
@section('meta_description', __('View detailed information and install your eSIM'))

@section('content')

<section class="bg-[#faf6ef] min-h-screen pt-28 pb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('my-esims') }}" 
               class="inline-flex items-center text-orange-500 hover:text-orange-600 font-medium">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                {{ __('Back to My eSIMs') }}
            </a>
        </div>

        {{-- Balance & Status Section --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Remaining Balance --}}
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-orange-500"></i>
                            <span class="text-sm font-medium text-gray-700">{{ __('Remaining Balance') }}</span>
                        </div>
                        <span class="text-xs px-2 py-1 bg-orange-100 text-orange-600 rounded-full font-medium">
                            {{ $activation->status ?? 'Active' }}
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">
                        {{ $activation->line_details['remaining_usage_gb'] ?? 0 }} GB
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ __('of') }} {{ $activation->line_details['allowed_usage_gb'] ?? 0 }} GB {{ __('total') }}
                    </div>
                </div>

                {{-- Remaining Days --}}
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-calendar-days text-blue-500"></i>
                        <span class="text-sm font-medium text-gray-700">{{ __('Remaining Days') }}</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">
                        {{ $activation->line_details['remaining_days'] ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ __('of') }} {{ $activation->line_details['remaining_days'] ?? 0 }} {{ __('days validity') }}
                    </div>
                </div>

                {{-- Activated On --}}
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-clock-rotate-left text-green-500"></i>
                        <span class="text-sm font-medium text-gray-700">{{ __('Activated On') }}</span>
                    </div>
                    <div class="text-lg font-bold text-gray-900 mb-1">
                        {{ $activation->activated_at ? $activation->activated_at->format('M d, Y') : 'Not activated' }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $activation->activated_at ? $activation->activated_at->diffForHumans() : 'Install to activate' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Main eSIM Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                {{-- Left Info --}}
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($activation->bundle?->image)
                            <img src="{{ asset('storage/'.$activation->bundle->image) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <i class="fa-solid fa-globe text-gray-400 text-xl"></i>
                        @endif
                    </div>
                    <div>
                        <h2 class="font-semibold text-lg text-gray-900">{{ $activation->bundle?->name ?? '' }}</h2>
                        @if( $activation->bundle?->is_country)
                         <span class="font-semibold text-lg text-gray-900">{{ $activation->bundle?->region ?? '' }}</span>
                        @endif
                        <div class="flex items-center gap-3 mt-2">
                            <div>
                                <p class="text-xs text-gray-500">ICCID</p>
                                <div class="flex items-center gap-2">
                                    <code id="iccid" class="text-xs bg-gray-100 px-2 py-1 rounded">
                                        {{ $activation->iccid ?? '—' }}
                                    </code>
                                    <button onclick="copyToClipboard('iccid')" class="text-gray-400 hover:text-gray-700">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="h-6 w-px bg-gray-200"></div>
                            <div>
                                <p class="text-xs text-gray-500">SM-DP+ Address</p>
                                <div class="flex items-center gap-2">
                                    <code id="smdp" class="text-xs bg-gray-100 px-2 py-1 rounded truncate max-w-[120px]">
                                        {{ $activation->msisdn ?? '—' }}
                                    </code>
                                    <button onclick="copyToClipboard('smdp')" class="text-gray-400 hover:text-gray-700">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Package Pills --}}
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-600 text-sm font-medium">
                        {{ $activation->refill?->title }} 
                    </span>
                </div>
            </div>
        </div>

        {{-- Auto Refill Section --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('Auto Refill') }}</h3>
                    <p class="text-sm text-gray-600">
                        {{ __('Automatically purchase a new data package when this one expires') }}
                    </p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="auto-refill-toggle" class="sr-only peer" 
                           {{ $activation->auto_refill ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                </label>
            </div>
        </div>

        {{-- Network Section --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Available Networks') }}</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($activation->bundle?->networks ?? [] as $network)
                <button onclick="showNetwork('{{ $network }}')" 
                        class="network-btn border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors text-center">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-tower-cell text-gray-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $network }}</span>
                </button>
                @endforeach
            </div>
            
            {{-- Current Network Display --}}
            <div id="current-network" class="mt-6 p-4 bg-gray-50 rounded-xl hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Currently using') }}</p>
                        <p id="selected-network" class="text-lg font-bold text-gray-900"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ __('Signal Strength') }}</p>
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-4 bg-green-500 rounded"></div>
                            <div class="w-1 h-6 bg-green-500 rounded"></div>
                            <div class="w-1 h-8 bg-green-500 rounded"></div>
                            <div class="w-1 h-6 bg-gray-300 rounded"></div>
                            <div class="w-1 h-4 bg-gray-300 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- IP & Privacy Section --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Connection Details') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- IP Location --}}
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-location-dot text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ __('IP Location') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Current connection location') }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('Country') }}</span>
                            <span class="font-medium text-gray-900">{{ $activation->ip_country ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('City') }}</span>
                            <span class="font-medium text-gray-900">{{ $activation->ip_city ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('IP Address') }}</span>
                            <div class="flex items-center gap-2">
                                <code class="text-sm font-medium">{{ $activation->ip_address ?? 'Not connected' }}</code>
                                @if($activation->ip_address)
                                <button onclick="copyToClipboard('{{ $activation->ip_address }}')" class="text-gray-400 hover:text-gray-700">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Privacy Status --}}
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ __('Privacy Status') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('Your connection security') }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-sm font-medium text-gray-900">{{ __('Encrypted') }}</span>
                            </div>
                            <i class="fa-solid fa-check text-green-500"></i>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-sm font-medium text-gray-900">{{ __('No Logs Kept') }}</span>
                            </div>
                            <i class="fa-solid fa-check text-green-500"></i>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-sm font-medium text-gray-900">{{ __('Private IP') }}</span>
                            </div>
                            <i class="fa-solid fa-check text-green-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        {{-- Ready to Use Section --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Ready to use your eSIM?') }}</h3>

            <div class="space-y-4">
                {{-- Share Buttons --}}
                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="font-medium text-gray-900 mb-3">{{ __('Share eSIM Details') }}</p>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="shareViaWhatsApp()" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl font-medium transition-colors">
                            <i class="fa-brands fa-whatsapp"></i>
                            WhatsApp
                        </button>
                        <button onclick="shareViaTwitter()" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-blue-400 hover:bg-blue-500 text-white rounded-xl font-medium transition-colors">
                            <i class="fa-brands fa-twitter"></i>
                            Twitter
                        </button>
                        <button onclick="shareViaInstagram()" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-medium transition-colors">
                            <i class="fa-brands fa-instagram"></i>
                            Instagram
                        </button>
                        <button onclick="copyAllDetails()" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-gray-700 hover:bg-gray-800 text-white rounded-xl font-medium transition-colors">
                            <i class="fa-regular fa-copy"></i>
                            Copy All
                        </button>
                    </div>
                </div>

                {{-- Setup Guide --}}
           {{-- Ready to Use Section --}} <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8"> <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Ready to use your eSIM?') }}</h3> <div class="space-y-4"> {{-- Setup Guide --}} <div class="bg-gray-50 rounded-2xl p-6 space-y-5"> <div> <h4 class="text-base font-semibold text-gray-900 mb-2"> {{ __('Setup guide') }} </h4> <p class="text-sm text-gray-600 leading-relaxed"> {{ __('Your eSIM setup consists of two stages: installation and activation. You can install the eSIM at any time, but activation requires you to be in a supported country.') }} </p> <p class="text-sm text-gray-600 mt-2"> {{ __('For best results, install before travel and activate once you arrive at your destination.') }} </p> </div> {{-- iOS Detailed Guide --}} <details class="bg-white rounded-xl border border-gray-200 overflow-hidden"> <summary class="flex items-center justify-between cursor-pointer px-4 py-4 hover:bg-gray-50"> <div class="flex items-center gap-3"> <i class="fa-brands fa-apple text-lg text-gray-800"></i> <span class="font-medium text-gray-900"> {{ __('iPhone (iOS) Installation & Activation') }} </span> </div> <i class="fa-solid fa-chevron-down text-sm text-gray-400"></i> </summary> <div class="px-4 pb-4 pt-2 space-y-6"> {{-- Installation Section --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">Installation</h5> <div class="space-y-3"> <div> <p class="font-medium text-gray-800 text-sm mb-1">Method 1: Direct eSIM Installation Link</p> <p class="text-xs text-gray-600"> If your device runs iOS 17.4 or above, use the Direct eSIM Installation Link from your account. </p> </div> <div> <p class="font-medium text-gray-800 text-sm mb-1">Method 2: QR Code</p> <ul class="text-xs text-gray-600 space-y-1"> <li>• Go to Settings → Cellular → Add Cellular Plan</li> <li>• Position the QR code in the frame and scan it</li> <li>• Select OK to confirm installation</li> <li class="text-gray-500 mt-2"> Tip: On iOS 17.4+, open the QR code in Safari, press and hold for 2 seconds, then select "Add eSIM" </li> </ul> </div> <div> <p class="font-medium text-gray-800 text-sm mb-1">Method 3: Activation Code (Manual Entry)</p> <ul class="text-xs text-gray-600 space-y-1"> <li>• Go to Settings → Cellular → Add Cellular Plan</li> <li>• Tap "Enter Details Manually" below the scanner</li> <li>• Enter SM-DP+ Address & Activation Code</li> </ul> </div> </div> </div> {{-- Activation Section --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">Activation</h5> <ul class="text-xs text-gray-600 space-y-2"> <li>• Turn on your eSIM under Cellular/Mobile Plan</li> <li>• For dual SIM: Toggle "Allow Cellular Data Switching" as needed</li> <li>• Enable Data Roaming (turn off primary line to avoid carrier charges)</li> </ul> </div> {{-- Label Section --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">Recommended eSIM Label</h5> <p class="text-xs text-gray-600"> Label your eSIM as "{{ $activation->bundle?->name ?? 'Bundle_Name' }}" for easy identification. </p> <p class="text-xs text-gray-600 mt-1"> Go to Settings → Cellular → select eSIM → Cellular Plan Label to edit. </p> </div> {{-- Warning --}} <div> <p class="text-xs text-gray-700 font-medium mb-1">Important Notice</p> <p class="text-xs text-gray-600"> The eSIM QR code is unique and can be installed only once. If you lose your device or remove the eSIM, contact support for assistance. </p> </div> </div> </details> {{-- Android Detailed Guide --}} <details class="bg-white rounded-xl border border-gray-200 overflow-hidden"> <summary class="flex items-center justify-between cursor-pointer px-4 py-4 hover:bg-gray-50"> <div class="flex items-center gap-3"> <i class="fa-brands fa-android text-lg text-gray-800"></i> <span class="font-medium text-gray-900"> {{ __('Android Installation & Activation') }} </span> </div> <i class="fa-solid fa-chevron-down text-sm text-gray-400"></i> </summary> <div class="px-4 pb-4 pt-2 space-y-6"> {{-- General Android Section --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">General Android Setup</h5> <div class="space-y-3"> <div> <p class="font-medium text-gray-800 text-sm mb-1">Option 1: QR Code Scan</p> <ul class="text-xs text-gray-600 space-y-1"> <li>• Go to Settings → Network & Internet</li> <li>• Tap Add SIM → Download eSIM</li> <li>• Scan QR code and enter confirmation code if prompted</li> </ul> </div> <div> <p class="font-medium text-gray-800 text-sm mb-1">Option 2: Manual Entry</p> <ul class="text-xs text-gray-600 space-y-1"> <li>• Go to Settings → Network & Internet → Add Mobile Network</li> <li>• Select "Enter Code Manually"</li> <li>• Enter in format: LPA:1$SMDP+ADDRESS$ACTIVATIONCODE</li> </ul> </div> </div> </div> {{-- Device-Specific Sections --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">Device Specific Instructions</h5> <div class="space-y-4"> <div> <p class="font-medium text-gray-800 text-sm mb-1">Google Pixel</p> <ul class="text-xs text-gray-600 space-y-1"> <li>• Settings → Network & Internet → Mobile Network → "+"</li> <li>• Scan QR code or enter manually</li> <li>• Click Activate when prompted</li> <li class="text-gray-500 mt-1"> APN settings: Settings → Network & Internet → Mobile network → Advanced → APN </li> </ul> </div> <div> <p class="font-medium text-gray-800 text-sm mb-1">Samsung Galaxy</p> <ul class="text-xs text-gray-600 space-y-1"> <li>• Settings → Connections → SIM card manager</li> <li>• Add mobile plan → Scan Carrier QR code</li> <li>• Position QR code and scan</li> <li class="text-gray-500 mt-1"> APN: Settings → Connections → Mobile networks → Access Point Names </li> </ul> </div> </div> </div> {{-- Activation Section --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">Activation Steps</h5> <ul class="text-xs text-gray-600 space-y-2"> <li>• Turn on eSIM under Mobile Network</li> <li>• Enable Mobile Data</li> <li>• Enable Data Roaming (disable primary line first)</li> <li>• Check APN settings if no connectivity (set manually if needed)</li> </ul> </div> {{-- Label Section --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">eSIM Label Setup</h5> <p class="text-xs text-gray-600"> Label as "{{ $activation->bundle?->name ?? 'Bundle_Name' }}" for better identification. </p> <p class="text-xs text-gray-600 mt-1"> Samsung: Settings → Connections → SIM card manager → select eSIM → Name </p> <p class="text-xs text-gray-600"> Pixel: Settings → Network & internet → SIMs → select eSIM → edit label </p> </div> </div> </details> {{-- Windows Detailed Guide --}} <details class="bg-white rounded-xl border border-gray-200 overflow-hidden"> <summary class="flex items-center justify-between cursor-pointer px-4 py-4 hover:bg-gray-50"> <div class="flex items-center gap-3"> <i class="fa-brands fa-windows text-lg text-gray-800"></i> <span class="font-medium text-gray-900"> {{ __('Windows 10/11 Installation & Activation') }} </span> </div> <i class="fa-solid fa-chevron-down text-sm text-gray-400"></i> </summary> <div class="px-4 pb-4 pt-2 space-y-4"> {{-- Installation --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">Installation</h5> <ul class="text-xs text-gray-600 space-y-2"> <li>1. Ensure laptop is connected to Internet</li> <li>2. Open Settings → Network & Internet → Cellular</li> <li>3. Click Manage eSIM profiles → Add a new profile</li> <li>4. Scan the QR code using your camera or webcam</li> <li>5. Complete the download and installation process</li> </ul> </div> {{-- Activation --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">Activation & Switching</h5> <ul class="text-xs text-gray-600 space-y-2"> <li>• To activate: Settings → Network & Internet → Cellular → Manage eSIM profiles</li> <li>• Select profile → Use → Confirm "Yes" to use cellular data</li> <li>• To switch profiles: Select current profile → Stop using → Choose new profile → Use</li> </ul> </div> {{-- Label Section --}} <div> <h5 class="font-semibold text-gray-900 mb-3 text-sm">eSIM Label</h5> <p class="text-xs text-gray-600"> Label as "{{ $activation->bundle?->name ?? 'Bundle_Name' }}" for easy identification. </p> <p class="text-xs text-gray-600 mt-1"> Go to Settings → Network & Internet → Cellular → eSIM profiles, select profile and edit name. </p> </div> </div> </details> {{-- Notice --}} <div class="flex items-start gap-3 text-sm text-gray-700 pt-2"> <i class="fa-solid fa-circle-exclamation mt-0.5"></i> <div> <p class="font-medium">{{ __('Important Security Notice') }}</p> <p class="text-sm mt-1"> {{ __('Do not delete your eSIM after installation. Reinstallation may not be possible. Keep your activation code secure and contact support if you face any issues.') }} </p> </div> </div> </div>

                {{-- Install Card --}}
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="font-medium text-gray-900 mb-1">{{ __('Install your eSIM') }}</p>
                    <p class="text-sm text-gray-600 mb-3">
                        {{ __('Installation takes a few minutes and only needs to be done once.') }}
                    </p>
                    <button onclick="openInstallModal({{ $activation->id }})"
                        class="w-full border border-gray-300 rounded-xl py-3 text-sm font-medium hover:bg-gray-100">
                        {{ __('Install or Share') }}
                    </button>
                </div>

                <script type="application/json" id="activation-data-{{ $activation->id }}">
                    {!! json_encode([
                        'qr' => $activation->qr_code_url ?? '',
                        'smdp' => $activation->smdp_address ?? '',
                        'code' => $activation->activation_code ?? '',
                        'iccid' => $activation->iccid ?? '',
                        'lpa' => $activation->lpa_code ?? 'LPA:1$' . ($activation->smdp_address ?? '') . '$' . ($activation->activation_code ?? ''),
                        'direct_link' => $activation->direct_install_link ?? 'https://esim.eastcompace.com/install?code=' . ($activation->activation_code ?? ''),
                        'country' => $activation->bundle?->name ?? 'Unknown',
                        'remaining_data' => $activation->remaining_data ?? 0,
                        'remaining_days' => $activation->remaining_days ?? 0
                    ]) !!}
                </script>
            </div>
        </div>

        {{-- FAQs --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Installation & connection FAQs') }}</h3>

            <details class="border-b border-gray-100 py-4">
                <summary class="cursor-pointer font-medium text-gray-900 flex items-center justify-between">
                    <span>{{ __('When should I install my eSIM?') }}</span>
                    <i class="fa-solid fa-plus text-gray-400 text-sm"></i>
                </summary>
                <p class="text-sm text-gray-600 mt-3">
                    {{ __('We recommend installing before travel or upon arrival with Wi‑Fi. Installation can be done anywhere, but activation requires being in a supported country.') }}
                </p>
            </details>

            <details class="border-b border-gray-100 py-4">
                <summary class="cursor-pointer font-medium text-gray-900 flex items-center justify-between">
                    <span>{{ __('Where can I see that my eSIM is installed?') }}</span>
                    <i class="fa-solid fa-plus text-gray-400 text-sm"></i>
                </summary>
                <p class="text-sm text-gray-600 mt-3">
                    {{ __('Check your device mobile network or SIM settings. On iOS: Settings → Cellular. On Android: Settings → Network & Internet → SIMs. On Windows: Settings → Network & Internet → Cellular.') }}
                </p>
            </details>

            <details class="border-b border-gray-100 py-4">
                <summary class="cursor-pointer font-medium text-gray-900 flex items-center justify-between">
                    <span>{{ __('What if I lose my device or accidentally remove the eSIM?') }}</span>
                    <i class="fa-solid fa-plus text-gray-400 text-sm"></i>
                </summary>
                <p class="text-sm text-gray-600 mt-3">
                    {{ __('eSIM QR codes are single-use. Contact our support team immediately via chat or email for assistance with data transfer to a new eSIM.') }}
                </p>
            </details>

            <details class="py-4">
                <summary class="cursor-pointer font-medium text-gray-900 flex items-center justify-between">
                    <span>{{ __('Do I need to enable Data Roaming?') }}</span>
                    <i class="fa-solid fa-plus text-gray-400 text-sm"></i>
                </summary>
                <p class="text-sm text-gray-600 mt-3">
                    {{ __('Yes, enable Data Roaming for your eSIM line. To avoid extra charges from your primary carrier, turn off your primary line when using the eSIM abroad.') }}
                </p>
            </details>
        </div>
    </div>
</section>

<script>
function copyToClipboard(text) {
    if (typeof text === 'string') {
        navigator.clipboard.writeText(text);
    } else {
        const el = document.getElementById(text);
        if (el) navigator.clipboard.writeText(el.innerText);
    }
    
    const button = event?.currentTarget;
    if (button) {
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-check"></i>';
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 2000);
    }
}

function copyAllDetails() {
    const data = document.getElementById('activation-data-{{ $activation->id }}');
    const activationData = JSON.parse(data.textContent);
    
    const details = `
📱 eSIM Details - {{ $activation->bundle?->name }}

🔢 ICCID: ${activationData.iccid}
🌐 SM-DP+ Address: ${activationData.msisdn}
🔑 Activation Code: ${activationData.code}
📍 Destination: ${activationData.country}
📊 Remaining Data: ${activationData.remaining_data} GB
📅 Remaining Days: ${activationData.remaining_days}
🔗 Direct Install: ${activationData.direct_link}

Installed via Esimira
    `.trim();
    
    navigator.clipboard.writeText(details);
    
    // Show toast notification
    showToast('All details copied to clipboard!');
}

function shareViaWhatsApp() {
    const data = document.getElementById('activation-data-{{ $activation->id }}');
    const activationData = JSON.parse(data.textContent);
    
    const text = encodeURIComponent(
        `📱 Check out my eSIM for ${activationData.country}!\n\n` +
        `📍 Destination: ${activationData.country}\n` +
        `📊 Data: ${activationData.remaining_data} GB remaining\n` +
        `📅 Valid for: ${activationData.remaining_days} days\n` +
        `🔗 Install: ${activationData.direct_link}\n\n` +
        `Get your own eSIM from Esimira!`
    );
    
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareViaTwitter() {
    const data = document.getElementById('activation-data-{{ $activation->id }}');
    const activationData = JSON.parse(data.textContent);
    
    const text = encodeURIComponent(
        `📱 Using my eSIM from @EsimiraApp for ${activationData.country}!\n` +
        `🌍 ${activationData.remaining_data}GB data, ${activationData.remaining_days} days validity\n` +
        `🔗 Install: ${activationData.direct_link}\n\n` +
        `#eSIM #TravelSIM #Roaming`
    );
    
    window.open(`https://twitter.com/intent/tweet?text=${text}`, '_blank');
}

function shareViaInstagram() {
    // Instagram doesn't support direct sharing via URL, so we'll copy to clipboard
    const data = document.getElementById('activation-data-{{ $activation->id }}');
    const activationData = JSON.parse(data.textContent);
    
    const text = `📱 eSIM for ${activationData.country}\n\n` +
                 `📍 ${activationData.country}\n` +
                 `📊 ${activationData.remaining_data}GB data\n` +
                 `📅 ${activationData.remaining_days} days\n` +
                 `🔗 ${activationData.direct_link}\n\n` +
                 `#eSIM #Travel #Esimira #DigitalSIM`;
    
    navigator.clipboard.writeText(text);
    showToast('Details copied! Paste in Instagram caption.');
}

function showNetwork(network) {
    document.getElementById('selected-network').textContent = network;
    const networkDisplay = document.getElementById('current-network');
    networkDisplay.classList.remove('hidden');
    
    // Remove active class from all buttons
    document.querySelectorAll('.network-btn').forEach(btn => {
        btn.classList.remove('border-orange-500', 'bg-orange-50');
    });
    
    // Add active class to clicked button
    event.currentTarget.classList.add('border-orange-500', 'bg-orange-50');
}

function showToast(message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-3 rounded-xl shadow-lg transform transition-all duration-300 opacity-0 translate-y-4';
    toast.textContent = message;
    toast.id = 'toast-notification';
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-y-4');
        toast.classList.add('opacity-100', 'translate-y-0');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-4');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Add accordion animation
document.querySelectorAll('details').forEach(detail => {
    detail.addEventListener('toggle', function() {
        const icon = this.querySelector('summary i.fa-plus, summary i.fa-chevron-down');
        if (icon) {
            if (this.open) {
                if (icon.classList.contains('fa-plus')) {
                    icon.classList.replace('fa-plus', 'fa-minus');
                }
            } else {
                if (icon.classList.contains('fa-minus')) {
                    icon.classList.replace('fa-minus', 'fa-plus');
                }
            }
        }
    });
});

// Auto-refill toggle
document.getElementById('auto-refill-toggle')?.addEventListener('change', function() {
    const isEnabled = this.checked;
    
    // Send AJAX request to update auto-refill setting
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            auto_refill: isEnabled
        })
    })
    .then(response => response.json())
    .then(data => {
        showToast(isEnabled ? 'Auto-refill enabled' : 'Auto-refill disabled');
    })
    .catch(error => {
        console.error('Error:', error);
        this.checked = !isEnabled; // Revert toggle
        showToast('Failed to update setting');
    });
});
</script>

{{-- INCLUDE THE INSTALL MODAL COMPONENT --}}
@include('pages.install-modal')

@endsection