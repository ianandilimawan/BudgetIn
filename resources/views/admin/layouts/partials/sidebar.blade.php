        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-zinc-900 border-r border-zinc-200/80 dark:border-zinc-800/80 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-zinc-200/80 dark:border-zinc-800/80">
                    @if (isset($settings) && $settings->logo_type === 'image' && $settings->app_logo)
                        <img src="{{ \App\Services\FileUploadService::getFileUrl($settings->app_logo) }}"
                            alt="{{ $settings->app_name }}" class="h-10 max-w-full object-contain">
                    @else
                        <div class="flex items-center">
                            <img src="{{ asset('images/logo-icon.svg') }}" alt="B" class="w-8 h-8 rounded-xl shadow-xs mr-1">
                            <h1 class="text-base font-extrabold text-zinc-900 dark:text-white tracking-tight">
                                Budget<span class="text-emerald-500">In</span><span class="text-emerald-500">.</span>
                            </h1>
                        </div>
                    @endif
                    <button id="closeSidebar"
                        class="lg:hidden text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <!-- Navigation -->
                <nav id="sidebarNav" class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                    @if (isset($groupedMenus))
                        @foreach ($groupedMenus as $sectionTitle => $menus)
                            <div class="space-y-1">
                                @if ($sectionTitle)
                                    <div class="px-4 py-2 mb-2">
                                        <h3
                                            class="text-[10px] font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
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
                        @foreach ($menus as $menu)
                            <x-admin.menu-item :menu="$menu" />
                        @endforeach
                    @endif
                </nav>

                <!-- User Section -->
                <div id="tour-profile-sidebar" class="p-4 border-t border-zinc-200/80 dark:border-zinc-800/80">
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center p-2 -mx-2 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        @if(Auth::user()->avatar)
                            <img class="w-10 h-10 rounded-full object-cover"
                                src="{{ Storage::url(Auth::user()->avatar) }}"
                                alt="User">
                        @else
                            <img class="w-10 h-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff"
                                alt="User">
                        @endif
                        <div class="ml-3">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate w-32">{{ Auth::user()->email }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Sidebar spacer for desktop -->
        <div id="sidebarSpacer" class="hidden lg:block w-64 flex-shrink-0 transition-all duration-300 ease-in-out">
        </div>
