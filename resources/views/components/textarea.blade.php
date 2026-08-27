@props(['name', 'label' => null, 'value' => null])

<div>
    @if ($label)
        <label for="{{ $name }}"
            class="block text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400 mb-2">{{ $label }}</label>
    @endif
    <div>
        <textarea name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'block w-full rounded-xl border border-transparent bg-gray-50 dark:bg-gray-800/80 text-gray-900 dark:text-white shadow-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 py-3.5 px-4 transition-all duration-200']) }}>{{ old($name, $value) }}</textarea>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
