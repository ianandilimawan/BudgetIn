@extends('admin.layouts.guest')

@section('title', 'Login')

@section('content')
    <div id="loginContainer"
        class="min-h-screen flex items-center justify-center bg-zinc-50 dark:bg-zinc-950 transition-all duration-500 relative overflow-hidden">

        <!-- Theme Toggle -->
        <div class="absolute top-6 right-6 z-50 animate-fade-in-up" style="animation-delay: 0.2s;">
            <button id="themeToggle"
                class="p-2.5 bg-white/70 dark:bg-zinc-900/70 backdrop-blur-md border border-zinc-200/50 dark:border-zinc-700/50 rounded-xl text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white shadow-sm transition-all hover:scale-105">
                <x-heroicon-s-sun id="sunIcon" class="w-5 h-5" style="display: block;" />
                <x-heroicon-s-moon id="moonIcon" class="w-5 h-5" style="display: none;" />
            </button>
        </div>

        <!-- Animated Background Gradients (Glassmorphism) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-500/20 dark:bg-blue-600/10 blur-[120px] animate-pulse"
                style="animation-duration: 8s;"></div>
            <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[60%] rounded-full bg-purple-500/20 dark:bg-purple-600/10 blur-[120px] animate-pulse"
                style="animation-duration: 12s;"></div>
        </div>

        <div
            class="w-full max-w-5xl flex flex-col lg:flex-row bg-white/70 dark:bg-zinc-900/70 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-zinc-200/50 dark:shadow-black/50 border border-white/50 dark:border-zinc-800/50 overflow-hidden m-4 relative z-10 animate-fade-in-up">

            <!-- Left Side / Branding (Hidden on mobile) -->
            <div
                class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/80 dark:to-purple-900/80 text-zinc-900 dark:text-white relative overflow-hidden transition-colors duration-500">
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjMDAwIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] opacity-20 dark:opacity-20">
                </div>
                <div class="relative z-10">
                    @if (isset($settings) && $settings->logo_type === 'image' && $settings->app_logo)
                        <img src="{{ \App\Services\FileUploadService::getFileUrl($settings->app_logo) }}"
                            alt="{{ $settings->app_name }}"
                            class="h-12 object-contain mb-8 filter drop-shadow-sm dark:drop-shadow-lg">
                    @else
                        <div
                            class="h-12 w-12 bg-white/50 dark:bg-white/20 backdrop-blur-md rounded-xl shadow-sm dark:shadow-inner border border-zinc-200/50 dark:border-white/30 flex items-center justify-center mb-8 transition-colors duration-500">
                            <svg class="h-6 w-6 text-blue-600 dark:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                    @endif

                    <h1 class="text-4xl font-extrabold tracking-tight mb-4 drop-shadow-sm dark:drop-shadow-md">
                        Welcome to<br>
                        {{ isset($settings) && $settings->logo_text ? $settings->logo_text : (isset($settings) ? $settings->app_name : 'InForge') }}
                    </h1>
                    <p
                        class="text-zinc-600 dark:text-blue-100/90 text-lg max-w-sm drop-shadow-none dark:drop-shadow-sm transition-colors duration-500">
                        Experience the powerful and seamless management dashboard tailored for your business needs.
                    </p>
                </div>
            </div>

            <!-- Right Side / Form -->
            <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    @if (isset($settings) && $settings->logo_type === 'image' && $settings->app_logo)
                        <img src="{{ \App\Services\FileUploadService::getFileUrl($settings->app_logo) }}"
                            alt="{{ $settings->app_name }}" class="h-12 mx-auto object-contain">
                    @else
                        <div
                            class="mx-auto h-12 w-12 bg-zinc-100 dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 flex items-center justify-center">
                            <svg class="h-6 w-6 text-zinc-900 dark:text-zinc-100" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Sign in</h2>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Please enter your details to access your
                        account.</p>
                </div>

                <!-- Login Form -->
                <form class="space-y-5" action="{{ route('admin.login.post') }}" method="POST" id="loginForm">
                    @csrf

                    <!-- Single Error Message Banner -->
                    @if ($errors->any())
                        <div
                            class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 shadow-sm animate-fade-in-up">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm font-medium text-red-800 dark:text-red-300">
                                    {{ $errors->first() }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Email Field -->
                    <div>
                        <x-input-floating type="email" name="email" label="Email address" value="{{ old('email') }}"
                            required="true" :show-error="false" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="relative">
                            <input type="password" name="password" id="password" required placeholder=" "
                                class="block px-4 pb-3 pt-3 w-full text-sm text-zinc-900 bg-transparent rounded-xl border-2 border-zinc-200 appearance-none dark:text-white dark:border-zinc-700 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-500 peer transition-colors pr-12">
                            <label for="password"
                                class="absolute text-sm text-zinc-500 dark:text-zinc-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white/70 dark:bg-zinc-900/70 backdrop-blur px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text rounded-md">Password</label>

                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition-colors z-20">
                                <!-- Eye icon -->
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <!-- Eye off icon -->
                                <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-zinc-300 dark:border-zinc-600 rounded bg-transparent transition-colors">
                            <label for="remember-me"
                                class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" id="submitBtn"
                            class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span id="submitText">Sign in securely</span>
                            <svg id="submitSpinner" class="animate-spin ml-2 h-5 w-5 text-white hidden"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Footer Info -->
                <div class="text-center mt-10">
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                        © {{ date('Y') }} {{ isset($settings) ? $settings->app_name : 'InForge' }}. Created By
                        <a class="text-blue-600 dark:text-blue-400" href="https://intechstudio.id">Intech Studio</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.documentElement;
            // Get saved theme or default to DB setting to ensure proper rendering if head script missed it
            const dbTheme = '{{ \App\Models\Setting::getSettings()->theme_default ?? 'light' }}';
            let savedTheme = localStorage.getItem('adminTheme');
            if (!savedTheme) {
                if (dbTheme === 'system') {
                    savedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                } else {
                    savedTheme = dbTheme;
                }
            }

            const themeToggle = document.getElementById('themeToggle');
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');

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
            }

            applyTheme(savedTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const isDark = html.classList.contains('dark');
                    const newTheme = isDark ? 'light' : 'dark';
                    applyTheme(newTheme);
                    localStorage.setItem('adminTheme', newTheme);
                });
            }

            // Toggle Password Visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (togglePassword && passwordField && eyeIcon && eyeOffIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    eyeIcon.classList.toggle('hidden');
                    eyeOffIcon.classList.toggle('hidden');
                });
            }

            // Spinner on submit
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const btn = document.getElementById('submitBtn');
                    const text = document.getElementById('submitText');
                    const spinner = document.getElementById('submitSpinner');

                    if (btn && !btn.disabled) {
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        text.textContent = 'Signing in...';
                        spinner.classList.remove('hidden');
                    }
                });
            }
        });
    </script>
@endsection
