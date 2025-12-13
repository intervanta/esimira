<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
        <div class="fixed top-4 right-4 z-50">
        <x-language-selector />
    </div>
    <!-- Optional Header -->
    {{-- <header class="bg-white shadow-sm py-4 mb-6">
        <div class="max-w-7xl mx-auto px-4 flex justify-center">
            <x-application-logo class="w-24 h-24" />
        </div>
    </header> --}}

    <main class="flex justify-center items-center px-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-6 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </footer>
</body>
</html>
