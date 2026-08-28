@props(['menu'])

@php
    $url = '#';
    $routeName = $menu['route'] ?? null;
    $menuUrl = $menu['url'] ?? null;
    $menuSlug = $menu['slug'] ?? null;
    $menuIcon = $menu['icon'] ?? '';
    $menuName = $menu['name'] ?? 'Untitled';

    if ($routeName) {
        // Check if route exists
        if (Route::has($routeName)) {
            // Check if menu has slug/parameter for dynamic routes
            if (!empty($menuSlug)) {
                try {
                    // Try to get route parameters from Laravel route definition
                    $route = app('router')->getRoutes()->getByName($routeName);
                    $parameterNames = $route ? $route->parameterNames() : [];

                    // If route has parameters, use the first one with menu slug value
                    if (!empty($parameterNames)) {
                        $params = [$parameterNames[0] => $menuSlug];
                        $url = route($routeName, $params);
                    } else {
                        // Route doesn't have parameters, use without parameter
                        $url = route($routeName);
                    }
                } catch (\Exception $e) {
                    // If route doesn't accept parameter, use without parameter
                    $url = route($routeName);
                }
            } else {
                $url = route($routeName);
            }
        } else {
            // Route doesn't exist yet
            $url = 'javascript:void(0)';
        }
    } elseif ($menuUrl) {
        $url = $menuUrl;
    }

    // Check if current route is active
    $isActive = false;
    if ($routeName && Route::has($routeName)) {
        if (!empty($menuSlug)) {
            // Get current route parameters
            $currentParams = request()->route() ? request()->route()->parameters() : [];
            $routeMatches = request()->routeIs($routeName);

            // Check if any parameter matches the menu slug
            $paramMatches = in_array($menuSlug, $currentParams);

            $isActive = $routeMatches && $paramMatches;
        } else {
            // Support wildcard matching if route ends with .index, e.g., admin.users.index matches admin.users.*
            $matchPattern = $routeName;
            if (str_ends_with($routeName, '.index')) {
                $matchPattern = str_replace('.index', '.*', $routeName);
            }
            $isActive = request()->routeIs($routeName) || request()->routeIs($matchPattern);
        }
    }

    // Contextual feature badge (e.g. Profil & AI)
    $badgeText = null;
    $badgeClass = '';
    if (str_contains(strtolower($menuName), 'ai') || str_contains(strtolower($menuName), 'kesehatan')) {
        $badgeText = 'AI';
        $badgeClass = 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60';
    }
@endphp

<a href="{{ $url }}"
    class="group w-full flex items-center justify-between px-2.5 py-2 text-xs font-semibold rounded-xl transition-all duration-200 cursor-pointer {{ $isActive ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 font-bold border border-emerald-200/50 dark:border-emerald-800/50 shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/80 hover:text-zinc-900 dark:hover:text-white' }}">
    <div class="flex items-center gap-2.5 min-w-0">
        <div class="w-4 h-4 flex-shrink-0 flex items-center justify-center transition-colors duration-200 {{ $isActive ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-200' }}">
            {!! App\Helpers\MenuHelper::renderIcon($menuIcon, 'w-4 h-4') !!}
        </div>
        <span class="truncate">{{ $menuName }}</span>
    </div>
    @if ($badgeText)
        <span class="ml-2 px-1.5 py-0.2 text-[9px] font-extrabold rounded-md {{ $badgeClass }} flex-shrink-0">
            {{ $badgeText }}
        </span>
    @endif
</a>
