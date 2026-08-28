<header class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-100 dark:border-zinc-800/80 z-30 transition-all flex-shrink-0">
    <div class="flex items-center h-16 px-4 sm:px-6 justify-between">
        <!-- Left: Sidebar Toggle Button & Workspace Title -->
        <div class="flex items-center gap-3">
            <!-- Desktop Only: Sidebar Toggle Button -->
            <button id="toggleSidebar"
                class="hidden lg:inline-flex p-2 rounded-xl text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white bg-zinc-100/80 dark:bg-zinc-800/80 hover:bg-zinc-200/80 dark:hover:bg-zinc-700/80 border border-zinc-200/60 dark:border-zinc-700/60 focus:outline-none transition-all cursor-pointer"
                title="Sembunyikan / Tampilkan Sidebar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </button>

            <!-- Mobile Only: Brand Logo -->
            <div class="flex lg:hidden items-center gap-1.5">
                <img src="{{ asset('images/logo-icon.svg') }}" alt="BudgetIn" class="w-7 h-7 rounded-xl shadow-2xs">
                <span class="text-sm font-extrabold text-zinc-900 dark:text-white tracking-tight">
                    Budget<span class="text-emerald-500">In</span><span class="text-emerald-500">.</span>
                </span>
            </div>

            <!-- Desktop: Workspace Indicator -->
            <div class="hidden lg:flex items-center gap-2 text-xs">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="font-bold text-zinc-700 dark:text-zinc-300">Workspace</span>
            </div>
        </div>

        <!-- Right: Utility Actions -->
        <div class="flex items-center space-x-2 sm:space-x-3">
            <!-- Panduan Fitur Button -->
            <button type="button"
                onclick="window.dispatchEvent(new CustomEvent('start-onboarding-tour'))"
                class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/80 hover:bg-emerald-100 dark:hover:bg-emerald-900/80 border border-emerald-200/60 dark:border-emerald-800/60 rounded-xl transition-all cursor-pointer shadow-2xs"
                title="Buka Panduan & Tur Fitur Aplikasi">
                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>Panduan</span>
            </button>

            <!-- Dark Mode Toggle -->
            <button id="themeToggle"
                class="p-2 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white bg-zinc-100/80 dark:bg-zinc-800/80 border border-zinc-200/60 dark:border-zinc-700/60 hover:border-zinc-300 dark:hover:border-zinc-600 rounded-xl transition-all cursor-pointer"
                title="Ganti Mode Tema">
                <x-heroicon-s-sun id="sunIcon" class="w-4 h-4" style="display: block;" />
                <x-heroicon-s-moon id="moonIcon" class="w-4 h-4" style="display: none;" />
            </button>

            <!-- Mobile Only: User Profile Link -->
            <a id="tour-profile-nav" href="{{ route('admin.profile.index') }}" class="flex lg:hidden items-center p-1 rounded-full hover:ring-2 hover:ring-emerald-500/40 transition" title="Profil Saya">
                @if(Auth::user() && Auth::user()->avatar)
                    <img class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover ring-1 ring-zinc-200 dark:ring-zinc-700"
                        src="{{ Storage::url(Auth::user()->avatar) }}"
                        alt="User">
                @else
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </a>

            <!-- Desktop Logout Button -->
            <form method="POST" action="{{ route('admin.logout') }}" class="hidden sm:inline">
                @csrf
                <button type="submit"
                    class="px-3 py-1.5 text-xs font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 dark:hover:bg-rose-900/60 border border-rose-200/60 dark:border-rose-800/60 rounded-xl transition-all cursor-pointer shadow-2xs">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
