<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="h-full antialiased overflow-hidden">
        <div class="relative min-h-screen flex items-center justify-center bg-gray-900">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/login_bg/donasibg.jpg') }}" class="w-full h-full object-cover opacity-40 shadow-2xl" alt="Background">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/60 via-blue-900/40 to-black/80"></div>
                <div class="absolute inset-0 backdrop-blur-[2px]"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 w-full px-4 py-8 sm:px-0">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
