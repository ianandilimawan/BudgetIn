<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="admin-panel" id="adminHtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - {{ isset($settings) ? $settings->app_name : config('app.name', 'Laravel') }}</title>

    @if (isset($settings) && $settings->favicon)
        <link rel="icon" type="image/x-icon"
            href="{{ \App\Services\FileUploadService::getFileUrl($settings->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon"
            href="{{ \App\Services\FileUploadService::getFileUrl($settings->favicon) }}">
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
</body>

</html>
