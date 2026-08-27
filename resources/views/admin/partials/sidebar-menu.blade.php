@if (isset($groupedMenus))
    @foreach ($groupedMenus as $sectionTitle => $menus)
        <div class="space-y-1">
            @if ($sectionTitle)
                <div class="px-4 py-2 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
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
