@extends('admin.layouts.guest')

@section('title', 'Verifikasi OTP')

@section('content')
    <div id="loginContainer"
        class="min-h-screen flex items-center justify-center bg-zinc-50 dark:bg-zinc-950 transition-all duration-500 relative overflow-hidden">

        <!-- Animated Background Gradients (Glassmorphism) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-emerald-500/20 dark:bg-emerald-600/10 blur-[120px] animate-pulse" style="animation-duration: 8s;"></div>
            <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[60%] rounded-full bg-blue-500/20 dark:bg-blue-600/10 blur-[120px] animate-pulse" style="animation-duration: 12s;"></div>
        </div>

        <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white/70 dark:bg-zinc-900/70 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-zinc-200/50 dark:shadow-black/50 border border-white/50 dark:border-zinc-800/50 overflow-hidden m-4 relative z-10 animate-fade-in-up">

            <!-- Left Side / Branding (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-gradient-to-br from-emerald-50 via-teal-50 to-blue-50 dark:from-emerald-950/80 dark:via-teal-950/80 dark:to-blue-950/80 text-zinc-900 dark:text-white relative overflow-hidden transition-colors duration-500">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjMDAwIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] opacity-20"></div>
                <div class="relative z-10">
                    @if (isset($settings) && $settings->logo_type === 'image' && $settings->app_logo)
                        <img src="{{ \App\Services\FileUploadService::getFileUrl($settings->app_logo) }}"
                            alt="{{ $settings->app_name }}" class="h-12 object-contain mb-8 filter drop-shadow-lg">
                    @else
                        <img src="{{ asset('images/logo-icon.svg') }}" alt="BudgetIn" class="w-12 h-12 rounded-2xl shadow-lg shadow-emerald-500/30 mb-8">
                    @endif

                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300 mb-4">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Verifikasi Keamanan Akun
                    </div>

                    <h1 class="text-4xl font-extrabold tracking-tight mb-4 drop-shadow-md">
                        Otentikasi<br>Dua Langkah (OTP)
                    </h1>
                    <p class="text-zinc-600 dark:text-emerald-100/90 text-base max-w-sm drop-shadow-sm">
                        Melindungi data keuangan dan transaksi kas Anda dengan lapisan keamanan tambahan.
                    </p>
                </div>

                <div class="relative z-10 pt-6 text-xs text-zinc-500 dark:text-zinc-400">
                    Didukung oleh sistem keamanan role-based access control.
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
                        <img src="{{ asset('images/logo-icon.svg') }}" alt="BudgetIn" class="w-12 h-12 rounded-2xl shadow-lg shadow-emerald-500/30 mx-auto mb-3">
                    @endif
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Verifikasi Kode OTP</h2>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Masukkan 6 digit kode OTP yang dikirimkan ke email Anda.</p>
                </div>

                <!-- OTP Form -->
                <form class="space-y-6" action="{{ route('admin.login.otp.post') }}" method="POST" id="otpForm">
                    @csrf

                    <!-- OTP Field using floating style -->
                    <div>
                        <div class="relative">
                            <input type="text" name="otp" id="otp" required maxlength="6" autocomplete="off" placeholder=" "
                                class="block px-4 pb-3 pt-3 w-full text-center text-3xl font-mono tracking-widest text-zinc-900 bg-transparent rounded-xl border-2 border-zinc-200 appearance-none dark:text-white dark:border-zinc-700 dark:focus:border-emerald-500 focus:outline-none focus:ring-0 focus:border-emerald-500 peer transition-colors" value="{{ old('otp') }}">
                            <label for="otp"
                                class="absolute text-sm text-zinc-500 dark:text-zinc-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white/70 dark:bg-zinc-900/70 backdrop-blur px-2 peer-focus:px-2 peer-focus:text-emerald-600 peer-focus:dark:text-emerald-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text rounded-md">Kode Verifikasi</label>
                        </div>
                        @error('otp')
                            <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-500/30 transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Verifikasi Sekarang
                        </button>
                    </div>
                </form>

                <form action="{{ route('admin.login.otp.resend') }}" method="POST" class="text-center mt-6" id="resendForm">
                    @csrf
                    <button type="submit" id="resendBtn" disabled class="text-sm font-medium text-zinc-400 dark:text-zinc-500 transition-colors cursor-not-allowed">
                        Belum menerima kode? Kirim ulang OTP <span id="countdown"></span>
                    </button>
                    <div class="mt-6">
                        <a href="{{ route('admin.login') }}" class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:underline transition-colors">
                            &larr; Kembali ke Halaman Login
                        </a>
                    </div>
                </form>

                <!-- Footer Info -->
                <div class="text-center mt-10">
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                        © {{ date('Y') }} {{ isset($settings) ? $settings->app_name : 'BudgetIn' }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Background Theme setup
            const html = document.getElementById('adminHtml') || document.documentElement;

            const dbTheme = '{{ \App\Models\Setting::getSettings()->theme_default ?? "light" }}';
            let savedTheme = localStorage.getItem('adminTheme');
            if (!savedTheme) {
                if (dbTheme === 'system') {
                    savedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                } else {
                    savedTheme = dbTheme;
                }
            }
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }

            // Auto focus on OTP input
            const otpInput = document.getElementById('otp');
            if(otpInput) {
                otpInput.focus();
            }

            // Resend OTP Countdown logic
            const resendBtn = document.getElementById('resendBtn');
            const countdownSpan = document.getElementById('countdown');
            const resendForm = document.getElementById('resendForm');

            function startCountdown(duration) {
                let timer = duration;
                resendBtn.disabled = true;
                resendBtn.classList.remove('text-emerald-600', 'dark:text-emerald-400', 'hover:text-emerald-800', 'dark:hover:text-emerald-300');
                resendBtn.classList.add('text-zinc-400', 'dark:text-zinc-500', 'cursor-not-allowed');

                const interval = setInterval(function () {
                    countdownSpan.textContent = `(${timer}s)`;
                    if (--timer < 0) {
                        clearInterval(interval);
                        countdownSpan.textContent = '';
                        resendBtn.disabled = false;
                        resendBtn.classList.add('text-emerald-600', 'dark:text-emerald-400', 'hover:text-emerald-800', 'dark:hover:text-emerald-300');
                        resendBtn.classList.remove('text-zinc-400', 'dark:text-zinc-500', 'cursor-not-allowed');
                    }
                }, 1000);
            }

            // Check session storage for existing timer
            let availableAt = sessionStorage.getItem('otpResendAvailableAt');
            const now = Math.floor(Date.now() / 1000);

            if (availableAt && parseInt(availableAt) > now) {
                startCountdown(parseInt(availableAt) - now);
            } else {
                startCountdown(30);
                sessionStorage.setItem('otpResendAvailableAt', now + 30);
            }

            // On submit, reset timer
            resendForm.addEventListener('submit', function() {
                sessionStorage.setItem('otpResendAvailableAt', Math.floor(Date.now() / 1000) + 30);
            });
        });
    </script>
@endsection
