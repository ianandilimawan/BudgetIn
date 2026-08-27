@props(['name', 'label' => null, 'value' => null])

<div>
    @if ($label)
        <label for="{{ $name }}"
            class="block text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400 mb-2">{{ $label }}</label>
    @endif
    <div class="relative">
        @if (isset($iconSlot))
            <div class="absolute top-3.5 left-0 pl-4 flex items-start pointer-events-none text-gray-400 dark:text-gray-500">
                {{ $iconSlot }}
            </div>
        @endif
        <textarea name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'block w-full rounded-xl border border-transparent bg-gray-50 dark:bg-gray-800/80 text-gray-900 dark:text-white shadow-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 py-3.5 transition-all duration-200 ' . (isset($iconSlot) ? 'pl-11 pr-4' : 'px-4')]) }}>{{ old($name, $value) }}</textarea>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
