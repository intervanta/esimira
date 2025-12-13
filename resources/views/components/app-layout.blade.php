<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    @include('layouts.header')

    <div class="flex">
        <!-- Optional Sidebar -->
        <aside class="w-64 bg-white border-r min-h-screen hidden md:block">
            <!-- Sidebar links -->
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>

    <!-- Footer -->
    @include('layouts.footer')
</body>
</html>
