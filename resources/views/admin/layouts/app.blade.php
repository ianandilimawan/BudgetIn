<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="admin-panel" id="adminHtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($settings) ? $settings->app_name : config('app.name', 'Laravel') }} - Admin</title>

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

    <!-- Global libraries now bundled via Vite (app.js) -->

    @livewireStyles

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <script>
        // Initialize theme and sidebar state before body loads to prevent flash
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

            const html = document.documentElement;

            // Apply theme immediately before page renders
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }

            // Apply sidebar state
            const dbSidebarSetting = '{{ $settings->sidebar_style ?? "full" }}';
            const dbSidebar = dbSidebarSetting === 'collapsed' ? 'closed' : 'open';
            
            let savedSidebarState = localStorage.getItem('desktopSidebarState');
            if (!savedSidebarState) {
                savedSidebarState = dbSidebar;
            }
            
            if (savedSidebarState === 'closed') {
                html.classList.add('sidebar-closed');
            }
        })();
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        html, body {
            min-height: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            touch-action: manipulation;
            -webkit-overflow-scrolling: touch;
        }

        /* Normalize form inputs to clean medium/regular weight without mobile font bloat */
        input, select, textarea {
            font-weight: 400;
        }

        /* Clean cross-browser date input normalization for mobile Safari & Android */
        input[type="date"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        input[type="date"]::-webkit-date-and-time-value {
            text-align: left;
            min-height: 1.25em;
            line-height: inherit;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.15s ease;
            padding: 0;
            margin: 0;
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        .dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* Compact, neat placeholder styling across all inputs */
        ::placeholder,
        input::placeholder,
        textarea::placeholder,
        select::placeholder {
            font-size: 0.75rem !important; /* 12px / text-xs */
            font-weight: 400 !important;
            opacity: 0.75 !important;
        }

        /* Flatpickr Custom Styling for Clean Modern UI & Dark Mode */
        .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid #e4e4e7 !important;
            border-radius: 1rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            font-family: inherit !important;
            padding: 8px !important;
            z-index: 9999 !important;
        }

        .dark .flatpickr-calendar {
            background: #18181b !important;
            border-color: #27272a !important;
            color: #f4f4f5 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }

        .flatpickr-calendar::before,
        .flatpickr-calendar::after {
            display: none !important;
        }

        .flatpickr-months {
            padding: 4px 0 8px !important;
        }

        .flatpickr-month {
            color: inherit !important;
            fill: inherit !important;
        }

        .flatpickr-current-month {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: inherit !important;
            padding: 0 !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 600 !important;
            background: transparent !important;
            color: inherit !important;
        }

        .flatpickr-current-month input.cur-year {
            font-weight: 600 !important;
            color: inherit !important;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            color: #71717a !important;
            fill: #71717a !important;
            padding: 6px !important;
            border-radius: 0.5rem !important;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            color: #18181b !important;
            fill: #18181b !important;
            background: #f4f4f5 !important;
        }

        .dark .flatpickr-months .flatpickr-prev-month:hover,
        .dark .flatpickr-months .flatpickr-next-month:hover {
            color: #ffffff !important;
            fill: #ffffff !important;
            background: #27272a !important;
        }

        .flatpickr-weekdays {
            margin-bottom: 4px !important;
        }

        span.flatpickr-weekday {
            color: #a1a1aa !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
        }

        .flatpickr-day {
            border-radius: 0.625rem !important;
            font-size: 12px !important;
            color: #27272a !important;
            height: 34px !important;
            line-height: 34px !important;
            margin: 1px !important;
            border: 1px solid transparent !important;
        }

        .dark .flatpickr-day {
            color: #e4e4e7 !important;
        }

        .flatpickr-day:hover {
            background: #f4f4f5 !important;
            border-color: transparent !important;
        }

        .dark .flatpickr-day:hover {
            background: #27272a !important;
        }

        .flatpickr-day.today {
            border-color: #6366f1 !important;
            font-weight: 700 !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }

        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: #d4d4d8 !important;
        }

        .dark .flatpickr-day.flatpickr-disabled {
            color: #3f3f46 !important;
        }

        /* SweetAlert2 Popup Global & Mobile Modern Sizing */
        .swal2-container .swal2-popup {
            font-family: inherit !important;
            font-size: 0.875rem !important;
        }

        .swal2-title {
            font-size: 1rem !important;
            font-weight: 700 !important;
        }

        .swal2-html-container {
            font-size: 0.8125rem !important;
            line-height: 1.5 !important;
        }

        @media (max-width: 640px) {
            .swal2-container .swal2-popup {
                padding: 1rem !important;
                width: 90% !important;
                max-width: 330px !important;
                border-radius: 1.25rem !important;
            }

            .swal2-title {
                font-size: 0.9375rem !important;
            }

            .swal2-html-container {
                font-size: 0.75rem !important;
            }
        }

        /* Desktop sidebar collapsed state */
        @media (min-width: 1024px) {
            html.sidebar-closed #sidebar {
                transform: translateX(-100%) !important;
            }

            html.sidebar-closed #sidebarSpacer {
                width: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-zinc-50 dark:bg-zinc-950 font-sans text-sm antialiased text-zinc-900 dark:text-zinc-100 min-h-screen overflow-x-hidden flex flex-col" id="body">
    <div class="flex flex-1 w-full min-h-screen">
        <!-- Sidebar & Desktop Spacer -->
        @include('admin.layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 w-full min-h-screen">
            <!-- Top Navbar (Sticky on mobile & desktop) -->
            <div class="sticky top-0 z-30 w-full">
                @include('admin.layouts.partials.navbar')
            </div>

            <!-- Page Content -->
            <div class="flex-1 flex flex-col relative z-0 w-full">
                <main class="flex-1 p-3.5 sm:p-6 animate-fade-in-up pb-28 lg:pb-6">
                    @yield('content')
                </main>
                @include('admin.layouts.partials.footer')
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script src="{{ asset('js/powergrid.js') }}"></script>
    @livewireScripts

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 z-40 bg-zinc-950/50 backdrop-blur-sm hidden lg:hidden"></div>

    <script>
        // Toast notification function
        function showToast(message, type = 'info', duration = 5000) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toastId = 'toast-' + Date.now();
            const icons = {
                info: '<svg class="animate-spin h-5 w-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>',
                success: '<svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                error: '<svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                warning: '<svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
            };

            const colors = {
                info: 'bg-blue-50 dark:bg-blue-900 border-2 border-blue-600 dark:border-blue-600',
                success: 'bg-green-50 dark:bg-green-900 border-2 border-green-600 dark:border-green-600',
                error: 'bg-red-50 dark:bg-red-900 border-2 border-red-600 dark:border-red-600',
                warning: 'bg-yellow-50 dark:bg-yellow-900 border-2 border-yellow-600 dark:border-yellow-600'
            };

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className =
                `${colors[type] || colors.info} rounded-xl p-4 shadow-lg min-w-[300px] max-w-md transform transition-all duration-300 ease-in-out opacity-0 translate-x-8`;

            const messageLines = message.split('\n');
            toast.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3 mt-0.5">
                        ${icons[type] || icons.info}
                    </div>
                    <div class="flex-1">
                        ${messageLines.map(line => `<p class="text-sm font-semibold text-zinc-900 dark:text-white">${line}</p>`).join('')}
                    </div>
                    <button onclick="closeToast('${toastId}')" class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            `;

            container.appendChild(toast);

            // Force reflow for reliable CSS transition
            void toast.offsetWidth;

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-x-8');
                toast.classList.add('opacity-100', 'translate-x-0');
            });

            // Auto remove
            if (duration > 0) {
                setTimeout(() => {
                    closeToast(toastId);
                }, duration);
            }
        }

        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.remove('opacity-100', 'translate-x-0');
                toast.classList.add('opacity-0', 'translate-x-8');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Global AJAX error handler for 419 CSRF token errors
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ajaxError(function(event, xhr, settings, thrownError) {
                // Handle 419 CSRF token mismatch error
                if (xhr.status === 419) {
                    // Redirect to login page
                    window.location.href = '{{ route('admin.login') }}';
                }
            });
        }

        // Handle axios errors (if axios is available)
        if (window.axios) {
            axios.interceptors.response.use(
                function(response) {
                    return response;
                },
                function(error) {
                    // Handle 419 CSRF token mismatch error
                    if (error.response && error.response.status === 419) {
                        // Redirect to login page
                        window.location.href = '{{ route('admin.login') }}';
                        return Promise.reject(error);
                    }
                    return Promise.reject(error);
                }
            );
        }

        // Clean and reliable theme toggle
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');
            const html = document.getElementById('adminHtml');

            // Get saved theme or default to DB setting
            const dbTheme = '{{ $settings->theme_default ?? "light" }}';
            let savedTheme = localStorage.getItem('adminTheme');
            if (!savedTheme) {
                if (dbTheme === 'system') {
                    savedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                } else {
                    savedTheme = dbTheme;
                }
            }

            // Apply saved theme on load (theme is already applied in head, but sync icons)
            function applyTheme(theme) {
                if (theme === 'dark') {
                    html.classList.add('dark');
                    if (sunIcon) sunIcon.style.display = 'none';
                    if (moonIcon) moonIcon.style.display = 'block';
                } else {
                    html.classList.remove('dark');
                    if (sunIcon) sunIcon.style.display = 'block';
                    if (moonIcon) moonIcon.style.display = 'none';
                }
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: theme, isDark: theme === 'dark' } }));
            }

            // Initialize icons based on saved theme (theme class already applied in head)
            applyTheme(savedTheme);

            // Toggle function
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const isDark = html.classList.contains('dark');
                    const newTheme = isDark ? 'light' : 'dark';

                    applyTheme(newTheme);
                    localStorage.setItem('adminTheme', newTheme);
                });
            }
        });

        // Sidebar toggle for desktop & mobile
        const sidebar = document.getElementById('sidebar');
        const toggleSidebarBtn = document.getElementById('toggleSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const html = document.documentElement;

        if (toggleSidebarBtn) {
            toggleSidebarBtn.addEventListener('click', () => {
                if (window.innerWidth >= 1024) {
                    // Desktop toggle using html class
                    const isClosed = html.classList.contains('sidebar-closed');
                    if (isClosed) {
                        html.classList.remove('sidebar-closed');
                        localStorage.setItem('desktopSidebarState', 'open');
                    } else {
                        html.classList.add('sidebar-closed');
                        localStorage.setItem('desktopSidebarState', 'closed');
                    }

                    // Trigger window resize event after transition to fix DataTables/Chart widths
                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 310);
                } else {
                    // Mobile toggle
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                }
            });
        }

        function closeMobileSidebar() {
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', closeMobileSidebar);
        }

        if (overlay) {
            overlay.addEventListener('click', closeMobileSidebar);
        }
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ajaxForm', () => ({
                loading: false,
                async submit(e) {
                    // Sync TinyMCE editors to textareas
                    if (typeof tinymce !== 'undefined') {
                        tinymce.triggerSave();
                    }

                    const form = e.target;
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }
                    this.loading = true;
                    // Remove old errors
                    document.querySelectorAll('.text-red-500.ajax-error').forEach(el => el.remove());
                    
                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                            body: formData,
                        });
                        const data = await response.json();

                        if (response.ok && data.success) {
                            if (typeof showToast === 'function') showToast(data.message, 'success');
                            if (data.redirect) {
                                setTimeout(() => {
                                    const url = new URL(data.redirect, window.location.origin);
                                    url.searchParams.set('_t', Date.now());
                                    window.location.href = url.toString();
                                }, 1000);
                            } else {
                                this.loading = false;
                            }
                        } else if (response.status === 422 && data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    const errorEl = document.createElement('p');
                                    errorEl.className = 'text-red-500 text-xs mt-1 ajax-error';
                                    errorEl.textContent = data.errors[field][0];
                                    input.parentNode.appendChild(errorEl);
                                }
                            });
                            if (typeof showToast === 'function') showToast(data.message || 'Please fix the validation errors.', 'error');
                            this.loading = false;
                        } else {
                            throw new Error(data.message || 'Something went wrong');
                        }
                    } catch (error) {
                        if (typeof showToast === 'function') showToast(error.message || 'Failed to submit form.', 'error');
                        this.loading = false;
                    }
                }
            }));
        });
    </script>
    <x-toast />
    <x-confirm-delete-modal />
    @include('admin.layouts.partials.mobile_bottom_nav')
    @stack('scripts')

    <!-- PWA Service Worker & Install Prompt Registration -->
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

        // PWA Install Prompt Event Listener
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            const container = document.getElementById('pwaInstallContainer');
            if (container) container.style.display = 'flex';
        });

        document.addEventListener('DOMContentLoaded', function() {
            const installBtn = document.getElementById('pwaInstallBtn');
            if (installBtn) {
                installBtn.addEventListener('click', async function() {
                    if (!deferredPrompt) return;
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    if (outcome === 'accepted') {
                        const container = document.getElementById('pwaInstallContainer');
                        if (container) container.style.display = 'none';
                    }
                    deferredPrompt = null;
                });
            }
        });

        window.addEventListener('appinstalled', function() {
            const container = document.getElementById('pwaInstallContainer');
            if (container) container.style.display = 'none';
            deferredPrompt = null;
        });
    </script>
</body>

</html>
