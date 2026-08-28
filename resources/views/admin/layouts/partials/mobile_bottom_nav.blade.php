<div x-data="{ mobileNavOpen: false }">
    <!-- Native Mobile Bottom Navigation Bar (Fixed at bottom on Mobile) -->
    <nav class="fixed bottom-0 inset-x-0 z-40 lg:hidden bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border-t border-zinc-200/80 dark:border-zinc-800 shadow-2xl pb-[env(safe-area-inset-bottom,0px)]">
        <div class="grid grid-cols-5 items-end justify-items-center h-15 px-2">
            <!-- 1. Beranda / Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex flex-col items-center justify-center w-full py-1 transition-colors {{ request()->routeIs('admin.dashboard*') ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('admin.dashboard*') ? '2.3' : '1.8' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    @if(request()->routeIs('admin.dashboard*'))
                        <span class="absolute -top-1 right-0 w-1.5 h-1.5 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-zinc-900"></span>
                    @endif
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight {{ request()->routeIs('admin.dashboard*') ? 'font-bold' : 'font-medium' }}">Beranda</span>
            </a>

            <!-- 2. Mutasi Transaksi -->
            <a href="{{ route('admin.cash_transactions.index') }}"
               class="flex flex-col items-center justify-center w-full py-1 transition-colors {{ request()->routeIs('admin.cash_transactions*') ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('admin.cash_transactions*') ? '2.3' : '1.8' }}" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                    </svg>
                    @if(request()->routeIs('admin.cash_transactions*'))
                        <span class="absolute -top-1 right-0 w-1.5 h-1.5 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-zinc-900"></span>
                    @endif
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight {{ request()->routeIs('admin.cash_transactions*') ? 'font-bold' : 'font-medium' }}">Mutasi</span>
            </a>

            <!-- 3. Raised Quick Action (⚡ Catat) -->
            <div id="tour-mobile-catat" class="flex flex-col items-center justify-end w-full pb-0.5">
                @if(request()->routeIs('admin.dashboard*'))
                    <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-quick-modal'))"
                            class="w-12 h-12 -translate-y-3 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center shadow-lg shadow-emerald-600/40 ring-4 ring-zinc-50 dark:ring-zinc-950 active:scale-90 transition-transform cursor-pointer"
                            title="Catat Transaksi Cepat">
                        <svg class="w-6 h-6 text-white stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                @else
                    <a href="{{ route('admin.cash_transactions.create') }}"
                       class="w-12 h-12 -translate-y-3 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center shadow-lg shadow-emerald-600/40 ring-4 ring-zinc-50 dark:ring-zinc-950 active:scale-90 transition-transform cursor-pointer"
                       title="Tambah Transaksi Baru">
                        <svg class="w-6 h-6 text-white stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </a>
                @endif
                <span class="text-[9px] font-bold text-zinc-600 dark:text-zinc-400 -translate-y-2">Catat</span>
            </div>

            <!-- 4. Dompet / Kas -->
            <a href="{{ route('admin.cash_accounts.index') }}"
               class="flex flex-col items-center justify-center w-full py-1 transition-colors {{ request()->routeIs('admin.cash_accounts*') ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('admin.cash_accounts*') ? '2.3' : '1.8' }}" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    @if(request()->routeIs('admin.cash_accounts*'))
                        <span class="absolute -top-1 right-0 w-1.5 h-1.5 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-zinc-900"></span>
                    @endif
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight {{ request()->routeIs('admin.cash_accounts*') ? 'font-bold' : 'font-medium' }}">Dompet</span>
            </a>

            <!-- 5. Menu Lainnya (Opens Mobile Bottom Sheet) -->
            <button type="button"
                    @click="mobileNavOpen = true"
                    class="flex flex-col items-center justify-center w-full py-1 transition-colors text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 cursor-pointer">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-medium">Menu</span>
            </button>
        </div>
    </nav>

    <!-- Mobile Native Bottom Sheet Menu Drawer -->
    <div x-show="mobileNavOpen" style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="mobileNavOpen = false"
         class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-end justify-center lg:hidden">

        <div @click.away="mobileNavOpen = false"
             x-show="mobileNavOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="w-full bg-white dark:bg-zinc-900 rounded-t-3xl border-t border-zinc-200 dark:border-zinc-800 shadow-2xl p-5 pb-[max(2rem,calc(env(safe-area-inset-bottom,0px)+1.5rem))] max-h-[85vh] overflow-y-auto space-y-4">

            <!-- Grab Handle -->
            <div class="w-12 h-1 bg-zinc-300 dark:bg-zinc-700 rounded-full mx-auto -mt-1 mb-2"></div>

            <!-- User Info Header -->
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                <div class="flex items-center gap-3 min-w-0">
                    @if(Auth::user() && Auth::user()->avatar)
                        <img class="w-10 h-10 rounded-full object-cover ring-2 ring-emerald-500/30"
                             src="{{ Storage::url(Auth::user()->avatar) }}"
                             alt="Avatar">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-bold flex items-center justify-center text-sm shadow-sm ring-2 ring-emerald-500/30">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ Auth::user()->name ?? 'User' }}</h4>
                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>
                <button type="button" @click="mobileNavOpen = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Panduan Fitur Button -->
            <div>
                <button type="button" 
                        @click="mobileNavOpen = false; window.dispatchEvent(new CustomEvent('start-onboarding-tour'))"
                        class="w-full flex items-center justify-between p-3 rounded-2xl bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-indigo-500/10 border border-emerald-500/30 hover:border-emerald-500/50 transition cursor-pointer text-left">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-sm shadow-xs flex-shrink-0">
                            ✨
                        </div>
                        <div>
                            <p class="text-xs font-bold text-zinc-900 dark:text-white">Tur Panduan Fitur</p>
                            <p class="text-[10px] text-zinc-500 dark:text-zinc-400">Pelajari seluruh fungsi BudgetIn</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Mulai →</span>
                </button>
            </div>

            <!-- Modul Keuangan & Anggaran -->
            <div class="space-y-1.5">
                <h5 class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider px-1">Keuangan & Anggaran</h5>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.category_budgets.index') }}" @click="mobileNavOpen = false"
                       class="flex items-center gap-2.5 p-2.5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/40 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Target Anggaran</p>
                            <p class="text-[9px] text-zinc-400">Batas pos belanja</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.recurring_transactions.index') }}" @click="mobileNavOpen = false"
                       class="flex items-center gap-2.5 p-2.5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/40 hover:bg-blue-50 dark:hover:bg-blue-950/40 transition">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Transaksi Rutin</p>
                            <p class="text-[9px] text-zinc-400">Tagihan berulang</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.transaction_categories.index') }}" @click="mobileNavOpen = false"
                       class="flex items-center gap-2.5 p-2.5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/40 hover:bg-purple-50 dark:hover:bg-purple-950/40 transition">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/60 text-purple-600 dark:text-purple-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Kategori Transaksi</p>
                            <p class="text-[9px] text-zinc-400">Master pos kas</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.cash_accounts.index') }}" @click="mobileNavOpen = false"
                       class="flex items-center gap-2.5 p-2.5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/40 hover:bg-amber-50 dark:hover:bg-amber-950/40 transition">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Dompet & Kas</p>
                            <p class="text-[9px] text-zinc-400">Kelola rekening</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Modul Administrasi & Sistem (Hanya muncul jika berwenang) -->
            @if(auth()->user() && (auth()->user()->hasRole('Super Admin') || auth()->user()->hasPermission('view-users') || auth()->user()->hasPermission('view-activity-logs')))
            <div class="space-y-1.5">
                <h5 class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider px-1">Sistem & Akses</h5>
                <div class="grid grid-cols-2 gap-2">
                    @if(auth()->user()->hasPermission('view-users'))
                    <a href="{{ route('admin.finance_users.index') }}" @click="mobileNavOpen = false"
                       class="flex items-center gap-2.5 p-2.5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/40 hover:bg-sky-50 dark:hover:bg-sky-950/40 transition">
                        <div class="w-8 h-8 rounded-lg bg-sky-100 dark:bg-sky-900/60 text-sky-600 dark:text-sky-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Pengguna Finance</p>
                            <p class="text-[9px] text-zinc-400">Kontrol akun</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('admin.roles.index') }}" @click="mobileNavOpen = false"
                       class="flex items-center gap-2.5 p-2.5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/40 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Role & Hak Akses</p>
                            <p class="text-[9px] text-zinc-400">Hak permission</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('view-activity-logs'))
                    <a href="{{ route('admin.activity-logs.index') }}" @click="mobileNavOpen = false"
                       class="flex items-center gap-2.5 p-2.5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/40 hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Log Aktivitas</p>
                            <p class="text-[9px] text-zinc-400">Audit trail</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('admin.settings.index') }}" @click="mobileNavOpen = false"
                       class="flex items-center gap-2.5 p-2.5 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/40 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Pengaturan</p>
                            <p class="text-[9px] text-zinc-400">Sistem aplikasi</p>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Akun & Logout -->
            <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between gap-2">
                <a href="{{ route('admin.profile.index') }}" @click="mobileNavOpen = false"
                   class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-xs font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Profil Saya</span>
                </a>

                <form method="POST" action="{{ route('admin.logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-xs font-semibold transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
