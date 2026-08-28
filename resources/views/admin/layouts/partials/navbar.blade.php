<header class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-200/80 dark:border-zinc-800/80 z-30">
    <div class="flex items-center h-14 sm:h-16 px-4 sm:px-6 justify-between">
        <!-- Desktop: Sidebar Toggle Button -->
        <button id="toggleSidebar"
            class="hidden lg:inline-flex text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white focus:outline-none transition-colors">
            <x-heroicon-o-bars-3 class="w-6 h-6" />
        </button>

        <!-- Mobile: App Logo Brand Header -->
        <div class="flex lg:hidden items-center gap-2">
            <img src="{{ asset('images/logo-icon.svg') }}" alt="BudgetIn" class="w-7 h-7 rounded-lg shadow-2xs">
            <span class="text-sm font-extrabold text-zinc-900 dark:text-white tracking-tight">
                udget<span class="text-emerald-500">In</span><span class="text-emerald-500">.</span>
            </span>
        </div>

        <div class="flex items-center space-x-2 sm:space-x-3">
            <!-- Panduan Fitur Button -->
            <button type="button"
                onclick="window.dispatchEvent(new CustomEvent('start-onboarding-tour'))"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 border border-emerald-200/60 dark:border-emerald-800/60 rounded-xl transition cursor-pointer shadow-2xs"
                title="Buka Panduan & Tur Fitur Aplikasi">
                <span class="text-xs">✨</span>
                <span class="hidden sm:inline">Panduan</span>
            </button>

            <!-- Dark Mode Toggle -->
            <button id="themeToggle"
                class="p-2 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                title="Ganti Mode Tema">
                <x-heroicon-s-sun id="sunIcon" class="w-4 h-4 sm:w-5 sm:h-5" style="display: block;" />
                <x-heroicon-s-moon id="moonIcon" class="w-4 h-4 sm:w-5 sm:h-5" style="display: none;" />
            </button>

            <!-- Mobile Only: User Profile Link (On Desktop it's already in the sidebar) -->
            <a href="{{ route('admin.profile.index') }}" class="flex lg:hidden items-center p-1 rounded-full hover:ring-2 hover:ring-emerald-500/40 transition" title="Profil Saya">
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
                    class="px-3 py-1.5 text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-lg transition-colors cursor-pointer">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
