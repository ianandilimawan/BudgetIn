@extends('admin.layouts.guest')

@section('title', 'Verify OTP')

@section('content')
    <div id="loginContainer"
        class="min-h-screen flex items-center justify-center bg-zinc-50 dark:bg-zinc-950 transition-all duration-500 relative overflow-hidden">

        <!-- Animated Background Gradients (Glassmorphism) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-500/20 dark:bg-blue-600/10 blur-[120px] animate-pulse" style="animation-duration: 8s;"></div>
            <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[60%] rounded-full bg-purple-500/20 dark:bg-purple-600/10 blur-[120px] animate-pulse" style="animation-duration: 12s;"></div>
        </div>

        <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white/70 dark:bg-zinc-900/70 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-zinc-200/50 dark:shadow-black/50 border border-white/50 dark:border-zinc-800/50 overflow-hidden m-4 relative z-10 animate-fade-in-up">

            <!-- Left Side / Branding (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-gradient-to-br from-blue-600/90 to-purple-700/90 dark:from-blue-900/80 dark:to-purple-900/80 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] opacity-20"></div>
                <div class="relative z-10">
                    @if (isset($settings) && $settings->logo_type === 'image' && $settings->app_logo)
                        <img src="{{ \App\Services\FileUploadService::getFileUrl($settings->app_logo) }}"
                            alt="{{ $settings->app_name }}" class="h-12 object-contain mb-8 filter drop-shadow-lg">
                    @else
                        <div class="h-12 w-12 bg-white/20 backdrop-blur-md rounded-xl shadow-inner border border-white/30 flex items-center justify-center mb-8">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    @endif

                    <h1 class="text-4xl font-extrabold tracking-tight mb-4 drop-shadow-md">
                        Two-Factor<br>
                        Authentication
                    </h1>
                    <p class="text-blue-100/90 text-lg max-w-sm drop-shadow-sm">
                        Protecting your account with an extra layer of security.
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
                        <div class="mx-auto h-12 w-12 bg-gradient-to-br from-blue-600 to-indigo-600 dark:from-blue-500 dark:to-indigo-500 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Verify OTP</h2>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Please enter the 6-digit OTP code sent to your email.</p>
                </div>

                <!-- OTP Form -->
                <form class="space-y-6" action="{{ route('admin.login.otp.post') }}" method="POST" id="otpForm">
                    @csrf

                    <!-- OTP Field using floating style -->
                    <div>
                        <div class="relative">
                            <input type="text" name="otp" id="otp" required maxlength="6" autocomplete="off" placeholder=" "
                                class="block px-4 pb-3 pt-3 w-full text-center text-3xl font-mono tracking-widest text-zinc-900 bg-transparent rounded-xl border-2 border-zinc-200 appearance-none dark:text-white dark:border-zinc-700 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-500 peer transition-colors" value="{{ old('otp') }}">
                            <label for="otp"
                                class="absolute text-sm text-zinc-500 dark:text-zinc-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white/70 dark:bg-zinc-900/70 backdrop-blur px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text rounded-md">Verification Code</label>
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
                            class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Verify OTP
                        </button>
                    </div>
                </form>

                <form action="{{ route('admin.login.otp.resend') }}" method="POST" class="text-center mt-6" id="resendForm">
                    @csrf
                    <button type="submit" id="resendBtn" disabled class="text-sm font-medium text-zinc-400 dark:text-zinc-500 transition-colors cursor-not-allowed">
                        Didn't receive the code? Resend OTP <span id="countdown"></span>
                    </button>
                    <div class="mt-6">
                        <a href="{{ route('admin.login') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                            &larr; Back to Login
                        </a>
                    </div>
                </form>

                <!-- Footer Info -->
                <div class="text-center mt-10">
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                        © {{ date('Y') }} {{ isset($settings) ? $settings->app_name : 'InForge' }}. All rights reserved.
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
                resendBtn.classList.remove('text-blue-600', 'dark:text-blue-400', 'hover:text-blue-800', 'dark:hover:text-blue-300');
                resendBtn.classList.add('text-zinc-400', 'dark:text-zinc-500', 'cursor-not-allowed');

                const interval = setInterval(function () {
                    countdownSpan.textContent = `(${timer}s)`;
                    if (--timer < 0) {
                        clearInterval(interval);
                        countdownSpan.textContent = '';
                        resendBtn.disabled = false;
                        resendBtn.classList.add('text-blue-600', 'dark:text-blue-400', 'hover:text-blue-800', 'dark:hover:text-blue-300');
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
