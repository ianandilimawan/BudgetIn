<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="errorPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>419 - Page Expired | {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Initialize theme before body loads
        (function() {
            const savedTheme = localStorage.getItem('adminTheme') || localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-gray-900 dark:text-white mb-4">419</h1>
            <div class="w-24 h-1 bg-orange-600 mx-auto mb-6"></div>
        </div>

        <!-- Error Icon -->
        <div class="mb-8">
            <div
                class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-orange-100 dark:bg-orange-900/20 mb-6">
                <i class="fas fa-clock text-6xl text-orange-600 dark:text-orange-400"></i>
            </div>
        </div>

        <!-- Error Message -->
        {{-- Note: No exception details or debug information displayed - safe for production --}}
        <div class="mb-8">
            <h2 class="text-3xl font-semibold text-gray-900 dark:text-white mb-4">
                Page Expired
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">
                Sesi halaman Anda telah kedaluwarsa.
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-500">
                Ini biasanya terjadi karena halaman dibuka terlalu lama atau token keamanan telah berakhir.
            </p>
        </div>

    </div>

    <script>
        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.getElementById('errorPage');
            const savedTheme = localStorage.getItem('adminTheme') || localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            }
        });
    </script>
</body>

</html>
