@extends('admin.layouts.guest')

@section('title', 'Daftar Akun Finance')

@section('content')
    <div id="registerContainer"
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
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-emerald-500/20 dark:bg-emerald-600/10 blur-[120px] animate-pulse"
                style="animation-duration: 8s;"></div>
            <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[60%] rounded-full bg-blue-500/20 dark:bg-blue-600/10 blur-[120px] animate-pulse"
                style="animation-duration: 12s;"></div>
        </div>

        <div
            class="w-full max-w-5xl flex flex-col lg:flex-row bg-white/70 dark:bg-zinc-900/70 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-zinc-200/50 dark:shadow-black/50 border border-white/50 dark:border-zinc-800/50 overflow-hidden m-4 relative z-10 animate-fade-in-up">

            <!-- Left Side / Branding -->
            <div
                class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-gradient-to-br from-emerald-50 via-teal-50 to-blue-50 dark:from-emerald-950/80 dark:via-teal-950/80 dark:to-blue-950/80 text-zinc-900 dark:text-white relative overflow-hidden transition-colors duration-500">
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjMDAwIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] opacity-20 dark:opacity-20">
                </div>
                <div class="relative z-10">
                    @if (isset($settings) && $settings->logo_type === 'image' && $settings->app_logo)
                        <img src="{{ \App\Services\FileUploadService::getFileUrl($settings->app_logo) }}"
                            alt="{{ $settings->app_name }}"
                            class="h-12 object-contain mb-8 filter drop-shadow-sm dark:drop-shadow-lg">
                    @else
                        <img src="{{ asset('images/logo-icon.svg') }}" alt="BudgetIn" class="w-12 h-12 rounded-2xl shadow-lg shadow-emerald-500/30 mb-8">
                    @endif

                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300 mb-4">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Akses Penuh Akun Finance Pribadi
                    </div>

                    <h1 class="text-4xl font-extrabold tracking-tight mb-4 drop-shadow-sm dark:drop-shadow-md">
                        Mulai Kelola Arus Kas & Keuangan Anda
                    </h1>
                    <p class="text-zinc-600 dark:text-emerald-100/90 text-base max-w-sm">
                        Daftar akun sekarang dan dapatkan akses pencatatan transaksi kas, dompet keuangan multi-rekening, dan rekap visual terintegrasi.
                    </p>

                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-zinc-700 dark:text-zinc-200">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Data keuangan 100% aman & terisolasi</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-zinc-700 dark:text-zinc-200">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Manajemen banyak akun bank & e-wallet</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-zinc-700 dark:text-zinc-200">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Laporan rekap bulanan & ekspor Excel</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-6 text-xs text-zinc-500 dark:text-zinc-400">
                    Didukung oleh sistem keamanan role-based access control.
                </div>
            </div>

            <!-- Right Side / Form -->
            <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-14 flex flex-col justify-center">
                <!-- Mobile Header -->
                <div class="lg:hidden text-center mb-6">
                    <img src="{{ asset('images/logo-icon.svg') }}" alt="BudgetIn" class="w-12 h-12 rounded-2xl shadow-lg shadow-emerald-500/30 mx-auto mb-3">
                </div>

                <div class="text-center lg:text-left mb-6">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Daftar Akun Baru</h2>
                    <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Isi data di bawah ini untuk membuat akun Finance Anda.</p>
                </div>

                <!-- Register Form -->
                <form class="space-y-4" action="{{ route('admin.register.post') }}" method="POST" id="registerForm">
                    @csrf

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 shadow-sm animate-fade-in-up">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm font-medium text-red-800 dark:text-red-300">
                                    {{ $errors->first() }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Full Name -->
                    <div>
                        <x-input-floating type="text" name="name" label="Nama Lengkap" value="{{ old('name') }}" required="true" :show-error="false" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-floating type="email" name="email" label="Alamat Email" value="{{ old('email') }}" required="true" :show-error="false" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="relative">
                            <input type="password" name="password" id="password" required placeholder=" "
                                class="block px-4 pb-3 pt-3 w-full text-sm text-zinc-900 bg-transparent rounded-xl border-2 border-zinc-200 appearance-none dark:text-white dark:border-zinc-700 dark:focus:border-emerald-500 focus:outline-none focus:ring-0 focus:border-emerald-500 peer transition-colors pr-12">
                            <label for="password"
                                class="absolute text-sm text-zinc-500 dark:text-zinc-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white/70 dark:bg-zinc-900/70 backdrop-blur px-2 peer-focus:px-2 peer-focus:text-emerald-600 peer-focus:dark:text-emerald-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text rounded-md">
                                Password (min. 8 karakter)
                            </label>
                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition-colors z-20">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder=" "
                                class="block px-4 pb-3 pt-3 w-full text-sm text-zinc-900 bg-transparent rounded-xl border-2 border-zinc-200 appearance-none dark:text-white dark:border-zinc-700 dark:focus:border-emerald-500 focus:outline-none focus:ring-0 focus:border-emerald-500 peer transition-colors">
                            <label for="password_confirmation"
                                class="absolute text-sm text-zinc-500 dark:text-zinc-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white/70 dark:bg-zinc-900/70 backdrop-blur px-2 peer-focus:px-2 peer-focus:text-emerald-600 peer-focus:dark:text-emerald-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text rounded-md">
                                Konfirmasi Password
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button type="submit" id="submitBtn"
                            class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-500/30 transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <span id="submitText">Daftar Sekarang</span>
                            <svg id="submitSpinner" class="animate-spin ml-2 h-5 w-5 text-white hidden"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            Sudah memiliki akun?
                            <a href="{{ route('admin.login') }}" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Footer Info -->
                <div class="text-center mt-8">
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                        © {{ date('Y') }} {{ isset($settings) ? $settings->app_name : 'BudgetIn' }}. Created By
                        <a class="text-emerald-600 dark:text-emerald-400" href="https://intechstudio.id">Intech Studio</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.documentElement;
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
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    const btn = document.getElementById('submitBtn');
                    const text = document.getElementById('submitText');
                    const spinner = document.getElementById('submitSpinner');

                    if (btn && !btn.disabled) {
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        text.textContent = 'Mendaftarkan...';
                        spinner.classList.remove('hidden');
                    }
                });
            }
        });
    </script>
@endsection
