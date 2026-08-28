<footer
    class="app-footer mt-auto border-t border-zinc-200/80 dark:border-zinc-800/80 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md hidden lg:block">
    <div class="flex flex-row justify-between items-center gap-2">
        <div class="text-[10px] font-medium text-zinc-500 dark:text-zinc-400 text-left">
            &copy; {{ date('Y') }}
            <a href="/"
                class="font-bold text-zinc-800 dark:text-zinc-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                {{ isset($settings) ? $settings->app_name : config('app.name', 'BudgetIn') }}
            </a>
            <span class="text-zinc-300 dark:text-zinc-700 mx-1">•</span>
            <a href="https://intechstudio.id"
                class="font-medium text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Intech Studio</a>
        </div>

        <div class="flex items-center text-[10px] font-medium text-zinc-500 dark:text-zinc-400 flex-shrink-0">
            <div
                class="flex items-center justify-center px-1.5 py-0.5 rounded border border-zinc-200/80 dark:border-zinc-700/80 shadow-2xs ring-1 ring-black/5 dark:ring-white/5 font-mono text-[9px]">
                v2.1.0
            </div>
        </div>
    </div>
</footer>
