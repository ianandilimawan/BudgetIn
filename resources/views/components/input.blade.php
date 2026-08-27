@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'isCurrency' => false])

@php
    $isReadonly = $attributes->has('readonly') || $attributes->has('disabled');
    $baseClasses = 'block w-full rounded-xl border border-transparent text-gray-900 dark:text-white shadow-sm py-3.5 px-4 transition-all duration-200';
    $activeClasses = 'bg-gray-50 dark:bg-gray-800/80 focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500';
    $readonlyClasses = 'bg-gray-200/60 dark:bg-gray-900/60 opacity-75 cursor-not-allowed';
    $mergedClasses = $baseClasses . ' ' . ($isReadonly ? $readonlyClasses : $activeClasses);
@endphp
<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400 mb-2">{{ $label }}</label>
    @endif
    <div>
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => $mergedClasses]) }}
            value="{{ old($name, $value) }}" {!! $isCurrency ? 'data-currency="true"' : '' !!}>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
