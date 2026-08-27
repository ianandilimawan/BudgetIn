            <header class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-200/80 dark:border-zinc-800/80 z-40">
                <div class="flex items-center h-16 px-6 justify-between">
                    <button id="toggleSidebar"
                        class="text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white focus:outline-none transition-colors">
                        <x-heroicon-o-bars-3 class="w-6 h-6" />
                    </button>

                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button id="themeToggle"
                            class="p-2 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                            <x-heroicon-s-sun id="sunIcon" class="w-5 h-5" style="display: block;" />
                            <x-heroicon-s-moon id="moonIcon" class="w-5 h-5" style="display: none;" />
                        </button>

                        <!-- Notifications -->
                        {{-- <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open"
                                class="relative p-2 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 focus:outline-none transition-colors duration-200">
                                <x-heroicon-o-bell class="w-5 h-5" />
                                <!-- Ping animation for unread -->
                                <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                style="display: none;"
                                class="absolute right-0 mt-2 w-80 bg-white dark:bg-zinc-900 rounded-xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] dark:shadow-none py-1 border border-zinc-200/80 dark:border-zinc-800/80 z-50">
                                <div class="px-4 py-3 border-b border-zinc-200/80 dark:border-zinc-800/80 flex justify-between items-center">
                                    <h3 class="text-xs font-semibold text-zinc-900 dark:text-white uppercase tracking-wider">Notifications</h3>
                                    <span class="bg-zinc-100 text-zinc-800 text-[10px] font-medium px-2 py-0.5 rounded-full dark:bg-zinc-800 dark:text-zinc-300">3 New</span>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <!-- Dummy Item 1 (New/Unread) -->
                                    <a href="#" class="flex px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-blue-50/50 dark:bg-blue-900/20 hover:bg-blue-50 dark:hover:bg-blue-900/40 transition-colors duration-200 relative">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                                <x-heroicon-o-user class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                            </div>
                                        </div>
                                        <div class="w-full pl-3 pr-4">
                                            <div class="text-gray-900 dark:text-white text-sm mb-1.5 font-bold">New user registered</div>
                                            <div class="text-gray-600 dark:text-gray-300 text-xs">John Doe just created an account.</div>
                                            <div class="text-blue-600 dark:text-blue-400 text-xs mt-1 font-semibold">2 minutes ago</div>
                                        </div>
                                        <!-- Unread Dot -->
                                        <div class="absolute top-1/2 -translate-y-1/2 right-4 w-2 h-2 bg-blue-600 dark:bg-blue-500 rounded-full"></div>
                                    </a>
                                    <!-- Dummy Item 2 (New/Unread) -->
                                    <a href="#" class="flex px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-blue-50/50 dark:bg-blue-900/20 hover:bg-blue-50 dark:hover:bg-blue-900/40 transition-colors duration-200 relative">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/50 flex items-center justify-center">
                                                <x-heroicon-o-exclamation-triangle class="w-4 h-4 text-yellow-600 dark:text-yellow-400" />
                                            </div>
                                        </div>
                                        <div class="w-full pl-3 pr-4">
                                            <div class="text-gray-900 dark:text-white text-sm mb-1.5 font-bold">System Alert</div>
                                            <div class="text-gray-600 dark:text-gray-300 text-xs">High CPU usage detected on server.</div>
                                            <div class="text-blue-600 dark:text-blue-400 text-xs mt-1 font-semibold">1 hour ago</div>
                                        </div>
                                        <!-- Unread Dot -->
                                        <div class="absolute top-1/2 -translate-y-1/2 right-4 w-2 h-2 bg-blue-600 dark:bg-blue-500 rounded-full"></div>
                                    </a>
                                    <!-- Dummy Item 3 (Read/Old) -->
                                    <a href="#" class="flex px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center opacity-75">
                                                <x-heroicon-o-check-circle class="w-4 h-4 text-green-600 dark:text-green-400" />
                                            </div>
                                        </div>
                                        <div class="w-full pl-3">
                                            <div class="text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">Backup Completed</div>
                                            <div class="text-gray-500 dark:text-gray-500 text-xs">Daily database backup was successful.</div>
                                            <div class="text-gray-400 dark:text-gray-500 text-xs mt-1">12 hours ago</div>
                                        </div>
                                    </a>
                                </div>
                                <a href="#" class="block py-2 text-xs font-medium text-center text-zinc-500 bg-zinc-50/50 hover:bg-zinc-100/50 dark:bg-zinc-900/50 dark:hover:bg-zinc-800/80 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors duration-200 rounded-b-xl border-t border-zinc-100 dark:border-zinc-800/50">
                                    <div class="inline-flex items-center">
                                        <x-heroicon-o-eye class="w-4 h-4 mr-2" />
                                        View all
                                    </div>
                                </a>
                            </div>
                        </div> --}}


                        <!-- Logout -->
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>
