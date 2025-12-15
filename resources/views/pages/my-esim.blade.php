@extends('layouts.app')

@section('title', __('My eSIMs – Esimira'))
@section('meta_description', __('View and manage all purchased eSIMs with Esimira'))
@section('meta_keywords', __('my esim, esim plans, travel esim'))

@section('content')
<section class="bg-gray-50 min-h-screen py-16 pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- HEADER -->
        <div class="mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ __('My eSIMs') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('View, manage and install your eSIMs') }}</p>
        </div>

        @if($activations->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($activations as $activation)
                    <div class="relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- HEADER -->
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center">
                                    @if($activation->bundle?->image)
                                        <img src="{{ asset('storage/'.$activation->bundle->image) }}"
                                             class="w-full h-full object-cover"
                                             alt="{{ $activation->bundle?->name }}">
                                    @else
                                        <i class="fa-solid fa-globe text-gray-400 text-xl"></i>
                                    @endif
                                </div>
                                <h3 class="font-bold text-lg text-gray-900">
                                    {{ $activation->bundle?->name ?? __('Unknown Country') }}
                                </h3>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="p-6">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 mb-1">{{ __('Package') }}</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $activation->refill?->title ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="px-6 pb-6 grid grid-cols-2 gap-3">
                            <a href="{{ route('esim-details', $activation) }}"
                               class="border border-gray-300 rounded-xl py-3 text-center text-sm font-medium hover:bg-gray-50">
                                {{ __('View Details') }}
                            </a>
                            <button onclick="openInstallModal({{ $activation->id }})"
                                    class="bg-orange-500 hover:bg-orange-600 text-white rounded-xl py-3 text-sm font-semibold">
                                {{ __('Install or Share') }}
                            </button>
                        </div>

                        <!-- HIDDEN DATA -->
                        <script type="application/json" id="activation-data-{{ $activation->id }}">
                        {!! json_encode([
                            'qr' => $activation->qr_code_url ?? '',
                            'smdp' => $activation->smdp_address ?? '',
                            'code' => $activation->activation_code ?? '',
                            'iccid' => $activation->iccid ?? '',
                            'lpa' => $activation->lpa_code ?? 'LPA:1$' . ($activation->smdp_address ?? '') . '$' . ($activation->activation_code ?? ''),
                            'direct_link' => $activation->direct_install_link ?? 'https://esim.eastcompace.com/install?code=' . ($activation->activation_code ?? ''),
                            'country' => $activation->bundle?->name ?? 'Unknown'
                        ]) !!}
                        </script>
                    </div>
                @endforeach
            </div>

            @if($activations->hasPages())
                <div class="mt-12">
                    {{ $activations->links('pagination::tailwind') }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-3xl shadow-sm p-12 text-center">
                <i class="fa-solid fa-sim-card text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-2xl font-semibold mb-3">{{ __('No eSIMs yet') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('Browse plans to get started') }}</p>
                <a href="{{ route('plans') }}"
                   class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-full font-medium">
                    {{ __('Browse Plans') }}
                </a>
            </div>
        @endif
    </div>
</section>

{{-- INCLUDE THE INSTALL MODAL COMPONENT --}}
@include('pages.install-modal')

@include('partials._footer')
@endsection