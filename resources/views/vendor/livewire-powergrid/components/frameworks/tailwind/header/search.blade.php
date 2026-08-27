@if (data_get($setUp, 'header.searchInput'))
    <div class="flex flex-row mt-3 md:mt-0 w-full rounded-full flex justify-start sm:justify-center md:justify-end">
        <div class="group relative rounded-full w-full md:w-64 ml-auto">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="p-1 focus:outline-none focus:shadow-outline">
                    <x-livewire-powergrid::icons.search
                        class="{{ theme_style($theme, 'searchBox.iconSearch') }}"
                    />
                </span>
            </span>
            <input
                wire:model.live.debounce.700ms="search"
                type="text"
                class="{{ theme_style($theme, 'searchBox.input') }} pl-10 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700"
                placeholder="{{ trans('livewire-powergrid::datatable.placeholders.search') }}"
            >
            @if ($search)
                <span
                    class="absolute opacity-0 group-hover:opacity-100 transition-all inset-y-0 right-0 flex items-center"
                >
                    <span class="p-2 rounded-full focus:outline-none focus:shadow-outline cursor-pointer">
                        <a wire:click.prevent="$set('search','')">
                            <x-livewire-powergrid::icons.x
                                class="w-4 h-4 {{ theme_style($theme, 'searchBox.iconClose') }}"
                            />
                        </a>
                    </span>
                </span>
            @endif
        </div>
    </div>
@endif
