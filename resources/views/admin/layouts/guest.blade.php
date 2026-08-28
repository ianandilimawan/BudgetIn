<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="admin-panel" id="adminHtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - {{ isset($settings) ? $settings->app_name : config('app.name', 'Laravel') }}</title>

    <!-- PWA Web App Manifest & Mobile Capability Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#059669">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ isset($settings) ? $settings->app_name : 'BudgetIn' }}">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192x192.png">

    @if (isset($settings) && $settings->favicon)
        <link rel="icon" type="image/x-icon"
            href="{{ \App\Services\FileUploadService::getFileUrl($settings->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon"
            href="{{ \App\Services\FileUploadService::getFileUrl($settings->favicon) }}">
    @else
        <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
        <link rel="icon" type="image/svg+xml" href="/images/logo-icon.svg">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Initialize theme before body loads to prevent flash
        (function() {
            const dbTheme = '{{ $settings->theme_default ?? "light" }}';
            let savedTheme = localStorage.getItem('adminTheme');
            
            if (!savedTheme) {
                if (dbTheme === 'system') {
                    savedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                } else {
                    savedTheme = dbTheme;
                }
            }

            const html = document.getElementById('adminHtml') || document.documentElement;

            // Apply theme immediately before page renders
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="font-sans antialiased">
    @yield('content')
    <x-toast />
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('PWA ServiceWorker registered with scope:', registration.scope);
                    })
                    .catch(function(err) {
                        console.warn('PWA ServiceWorker registration failed:', err);
                    });
            });
        }
    </script>
</body>

</html>
