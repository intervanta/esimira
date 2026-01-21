<!doctype html>
<html lang="en" class="scroll-smooth">
 <meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Esimira - Global eSIM Connectivity | Travel Without Roaming')</title>
    
    {{-- Meta Tags --}}
    <meta name="description" content="@yield('description', 'Experience seamless global connectivity with Esimira eSIM services. Instant activation, zero roaming charges, and flexible plans for 190+ countries. Travel connected anywhere you go.')">
    <meta name="keywords" content="@yield('keywords', 'eSIM, global connectivity, travel SIM, roaming free, mobile data, international travel, digital SIM')">
    <meta name="author" content="Esimira">    
    {{-- Open Graph Meta Tags --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og:title', 'Esimira - Global eSIM Connectivity | Travel Without Roaming')">
    <meta property="og:description" content="@yield('og:description', 'Experience seamless global connectivity with Esimira eSIM services. Instant activation, zero roaming charges, and flexible plans for 190+ countries.')">
    <meta property="og:image" content="@yield('og:image', asset('images/og-image.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Esimira">
    
    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter:title', 'Esimira - Global eSIM Connectivity | Travel Without Roaming')">
    <meta name="twitter:description" content="@yield('twitter:description', 'Experience seamless global connectivity with Esimira eSIM services. Instant activation, zero roaming charges.')">
    <meta name="twitter:image" content="@yield('twitter:image', asset('images/twitter-image.jpg'))">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/75_16615.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    
    {{-- Preload & Preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://static.rocket.new">
    <link rel="preconnect" href="https://api.fontshare.com">
    
    {{-- CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    {{-- Fixed Font Awesome link --}}
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" 
          crossorigin="anonymous" 
          referrerpolicy="no-referrer" />
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700&display=swap" rel="stylesheet">
    
    {{-- Additional Head Content --}}
    @stack('head')
</head>

<body class="bg-white font-sans antialiased">
    {{-- Header --}}
    @include('partials._header')

    {{-- Main Content --}}
    <main class="min-h-screen w-full">
        @yield('content')
    </main>
<script>
    {{-- Scripts --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script type="module" src="https://static.rocket.new/rocket-web.js?_cfg=https%3A%2F%2Fesimiragl4057back.builtwithrocket.new&_be=https%3A%2F%2Fapplication.rocket.new&_v=0.1.9"></script>
    <script type="module" src="https://static.rocket.new/rocket-shot.js?v=0.0.1"></script>

    <script src="{{ asset('js/global.js') }}" defer></script>
    <script id="dhws-dataInjector" src="{{ asset('js/dhws-data-injector.js') }}" defer></script>
    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>

