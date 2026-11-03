<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('code') · @yield('title') · {{ config('app.name', 'Hotel Luxury') }}</title>
    {{-- Mandiri (tanpa asset build / database) supaya tetap tampil saat aplikasi bermasalah --}}
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 24px; color: #070625; background: #f8f8f8;
            font-family: Poppins, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .card {
            width: 100%; max-width: 440px; text-align: center; background: #fff; border-radius: 16px;
            padding: 40px 28px; box-shadow: 0 8px 30px rgba(10, 9, 50, .08);
        }
        .code { margin: 0; font-size: 64px; font-weight: 800; line-height: 1; color: #4041da; }
        h1 { margin: 16px 0 8px; font-size: 22px; font-weight: 600; }
        p { margin: 0; color: #757c98; font-size: 14px; line-height: 1.6; }
        .actions { margin-top: 28px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-block; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600;
            text-decoration: none; border: 1px solid #4041da; color: #4041da; background: #fff; cursor: pointer;
        }
        .btn-primary { background: #4041da; color: #fff; }
        .btn:focus-visible { outline: 3px solid #b3b4f5; outline-offset: 2px; }
    </style>
</head>
<body>
    <main class="card" role="main">
        <p class="code" aria-hidden="true">@yield('code')</p>
        <h1>@yield('title')</h1>
        <p>@yield('message')</p>
        <div class="actions">
            <a class="btn btn-primary" href="{{ url('/') }}">Kembali ke beranda</a>
            @yield('extra_actions')
        </div>
    </main>
</body>
</html>
