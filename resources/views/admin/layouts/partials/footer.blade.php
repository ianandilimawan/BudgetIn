<footer
    class="mt-auto py-3 px-4 pb-[max(5rem,calc(env(safe-area-inset-bottom,0px)+4rem))] sm:pb-3 border-t border-zinc-200/80 dark:border-zinc-800/80 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md">
    <div class="flex flex-col md:flex-row justify-between items-center gap-1.5">
        <div class="text-[10px] font-medium text-zinc-500 dark:text-zinc-400 text-center sm:text-left">
            &copy; {{ date('Y') }}
            <a href="/"
                class="font-bold text-zinc-800 dark:text-zinc-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">
                {{ isset($settings) ? $settings->app_name : config('app.name', 'BudgetIn') }}
            </a>.
            <span class="hidden sm:inline-block ml-0.5">
                Created by <a href="https://intechstudio.id"
                    class="font-bold text-zinc-800 dark:text-zinc-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">Intech
                    Studio</a>
            </span>
        </div>

        <div class="flex items-center text-[10px] font-medium text-zinc-500 dark:text-zinc-400">
            <div
                class="flex items-center justify-center px-1.5 py-0.5 rounded border border-zinc-200/80 dark:border-zinc-700/80 shadow-2xs ring-1 ring-black/5 dark:ring-white/5">
                v2.1.0
            </div>
        </div>
    </div>
</footer>
