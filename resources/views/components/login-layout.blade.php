<body class="font-sans antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm py-4">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-gray-800">
                {{ config('app.name') }}
            </a>

            <!-- Optional Links -->
            <nav class="space-x-4 text-gray-600 text-sm">
                <a href="{{ route('login') }}" class="hover:text-gray-800">{{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="hover:text-gray-800">{{ __('Register') }}</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>
</body>
