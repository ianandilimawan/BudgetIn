        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-zinc-900 border-r border-zinc-200/80 dark:border-zinc-800/80 transform -translate-x-full transition-all duration-300 ease-in-out lg:top-3.5 lg:bottom-3.5 lg:left-3.5 lg:h-[calc(100vh-1.75rem)] lg:rounded-2xl lg:shadow-xs lg:border lg:border-zinc-200/80 lg:dark:border-zinc-800 lg:translate-x-0 overflow-hidden flex flex-col">
            <div class="flex flex-col h-full min-h-0">
                <!-- Logo Header -->
                <div class="flex items-center justify-between h-16 px-4 sm:px-5 border-b border-zinc-100 dark:border-zinc-800/80 flex-shrink-0">
                    @if (isset($settings) && $settings->logo_type === 'image' && $settings->app_logo)
                        <img src="{{ \App\Services\FileUploadService::getFileUrl($settings->app_logo) }}"
                            alt="{{ $settings->app_name }}" class="h-9 max-w-full object-contain">
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5">
                            <img src="{{ asset('images/logo-icon.svg') }}" alt="B" class="w-7 h-7 rounded-xl shadow-xs">
                            <span class="text-base font-extrabold text-zinc-900 dark:text-white tracking-tight">
                                Budget<span class="text-emerald-500">In</span><span class="text-emerald-500">.</span>
                            </span>
                        </a>
                    @endif
                    <button id="closeSidebar"
                        class="lg:hidden p-1.5 rounded-lg text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <!-- Navigation -->
                <nav id="sidebarNav" class="flex-1 px-3 py-3.5 space-y-4 overflow-y-auto min-h-0">
                    @if (isset($groupedMenus))
                        @foreach ($groupedMenus as $sectionTitle => $menus)
                            <div class="space-y-1">
                                @if ($sectionTitle)
                                    <div class="px-2.5 py-1 mb-1">
                                        <h3 class="text-[10px] uppercase font-bold text-zinc-400 dark:text-zinc-500 tracking-wider">
                                            {{ $sectionTitle }}
                                        </h3>
                                    </div>
                                @endif
                                <div class="space-y-1">
                                    @foreach ($menus as $menu)
                                        <x-admin.menu-item :menu="$menu" />
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @elseif (isset($menus))
                        <div class="space-y-1">
                            @foreach ($menus as $menu)
                                <x-admin.menu-item :menu="$menu" />
                            @endforeach
                        </div>
                    @endif
                </nav>

                <!-- User Section -->
                <div id="tour-profile-sidebar" class="p-3 border-t border-zinc-100 dark:border-zinc-800/80 flex-shrink-0">
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/60 transition-colors group">
                        @if(Auth::user()->avatar)
                            <img class="w-9 h-9 rounded-xl object-cover ring-2 ring-emerald-500/20 flex-shrink-0"
                                src="{{ Storage::url(Auth::user()->avatar) }}"
                                alt="User">
                        @else
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-black text-xs flex items-center justify-center shadow-xs flex-shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-[10px] text-zinc-400 truncate">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-zinc-400 group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-transform group-hover:translate-x-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Sidebar spacer for desktop -->
        <div id="sidebarSpacer" class="hidden lg:block w-64 flex-shrink-0 transition-all duration-300 ease-in-out">
        </div>
