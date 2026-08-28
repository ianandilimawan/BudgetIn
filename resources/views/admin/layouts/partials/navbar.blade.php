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
            <!-- Dark Mode Toggle -->
            <button id="themeToggle"
                class="p-2 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                title="Ganti Mode Tema">
                <x-heroicon-s-sun id="sunIcon" class="w-4 h-4 sm:w-5 sm:h-5" style="display: block;" />
                <x-heroicon-s-moon id="moonIcon" class="w-4 h-4 sm:w-5 sm:h-5" style="display: none;" />
            </button>

            <!-- User Profile Link (Mobile & Desktop) -->
            <a href="{{ route('admin.profile.index') }}" class="flex items-center p-1 rounded-full hover:ring-2 hover:ring-emerald-500/40 transition">
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
