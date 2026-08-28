<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="admin-panel lg:overflow-hidden" id="adminHtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($settings) ? $settings->app_name : config('app.name', 'Laravel') }} - Admin</title>

    @if (isset($settings) && $settings->favicon)
        <link rel="icon" type="image/x-icon"
            href="{{ \App\Services\FileUploadService::getFileUrl($settings->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon"
            href="{{ \App\Services\FileUploadService::getFileUrl($settings->favicon) }}">
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

        /* Prevent auto-zoom on iOS when tapping/focusing inputs */
        @media screen and (max-width: 768px) {
            input, select, textarea {
                font-size: 16px !important;
            }
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

<body class="bg-zinc-50 dark:bg-zinc-950 font-sans text-sm antialiased text-zinc-900 dark:text-zinc-100 min-h-screen min-h-[100dvh] overflow-x-hidden flex flex-col" id="body">
    <div class="flex flex-1 w-full min-h-screen min-h-[100dvh] lg:h-screen lg:overflow-hidden">
        <!-- Sidebar & Desktop Spacer -->
        @include('admin.layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 w-full min-h-screen lg:min-h-0 lg:h-full lg:overflow-hidden lg:ml-0">
            <!-- Top Navbar (Sticky on mobile, static on desktop) -->
            <div class="sticky top-0 z-30 w-full">
                @include('admin.layouts.partials.navbar')
            </div>

            <!-- Page Content (Window/body scrolls naturally on mobile, inner overflow-y-auto on desktop) -->
            <div class="flex-1 flex flex-col relative z-0 w-full lg:overflow-y-auto overscroll-y-contain" style="-webkit-overflow-scrolling: touch;">
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
</body>

</html>
