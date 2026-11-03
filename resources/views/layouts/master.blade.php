<!-- Struktur Master HTML Utama -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4041DA">
    <meta name="robots" content="@yield('robots', 'index,follow')">
    <meta name="description" content="@yield('meta_description', 'Cari dan pesan hotel dengan mudah: bandingkan kamar, cek ketersediaan, dan booking dalam beberapa langkah.')">

    <title>@hasSection('title')@yield('title') · @endif{{ config('app.name', 'Hotel Luxury') }}</title>

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name', 'Hotel Luxury') }}">
    <meta property="og:title" content="@hasSection('title')@yield('title') · @endif{{ config('app.name', 'Hotel Luxury') }}">
    <meta property="og:description" content="@yield('meta_description', 'Cari dan pesan hotel dengan mudah: bandingkan kamar, cek ketersediaan, dan booking dalam beberapa langkah.')">
    <meta property="og:url" content="{{ url()->current() }}">
    @stack('meta')

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    @stack('before-styles')
    {{-- import css (dimuat sekali; style tambahan halaman di-push sesudahnya agar menang di cascade) --}}
    <link href="{{ asset('output.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @stack('after-styles')
</head>

<body class="font-poppins text-[#070625] bg-gray-50 antialiased">

    @yield('content')

    @include('partials.form-lock')

    @stack('before-scripts')
    {{-- import java script --}}
    @stack('after-scripts')
</body>

</html>
