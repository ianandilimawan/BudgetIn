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
@endphp

<a href="{{ $url }}"
    class="group flex items-center px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/60 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-lg transition-all duration-200 cursor-pointer {{ $isActive ? 'bg-zinc-100 dark:bg-zinc-800/60 text-zinc-900 dark:text-zinc-100 font-semibold shadow-sm' : '' }}">
    <div
        class="w-4 h-4 mr-3 flex items-center justify-center menu-icon-container group-hover:scale-110 transition-transform duration-200">
        {!! App\Helpers\MenuHelper::renderIcon($menuIcon) !!}
    </div>
    <span
        class="group-hover:text-zinc-900 dark:group-hover:text-white transition-colors duration-200">{{ $menuName }}</span>
</a>
