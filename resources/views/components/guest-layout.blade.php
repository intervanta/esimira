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

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('layouts.footer')
</body>
</html>
