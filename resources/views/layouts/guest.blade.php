<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AdminPanel') }}</title>

        <!-- Fonts (Mengganti Figtree dengan Poppins) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-poppins text-[#070625] antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
            
            <!-- Logo Section -->
            <div>
                <a href="/" class="flex justify-center mb-4">
                    <!-- Logo Teks Kustom pengganti x-application-logo -->
                    <span class="font-extrabold text-4xl text-indigo-600 tracking-tight">
                        Admin<span class="text-gray-800">Panel</span>
                    </span>
                </a>
            </div>

            <!-- Card Container -->
            <!-- Disesuaikan shadow dan radiusnya agar konsisten dengan UI 'Manage Cities' -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-sm border border-gray-100 overflow-hidden sm:rounded-xl flex flex-col gap-y-4">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>