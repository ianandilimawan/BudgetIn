@props([
    'type' => 'text',
    'name',
    'id' => null,
    'label',
    'value' => '',
    'required' => false,
    'isCurrency' => false,
    'class' => '',
    'readonly' => false,
    'showError' => true,
])

@php
    $id = $id ?? $name;
    $isReadonly = $readonly || $attributes->has('readonly') || $attributes->has('disabled');
    $baseClasses =
        'block px-4 pb-2.5 pt-4.5 w-full text-base text-gray-900 rounded-xl border appearance-none dark:text-white peer transition-colors';
    $activeClasses =
        'bg-transparent border-gray-300 dark:border-gray-700 dark:focus:border-indigo-500 focus:outline-none focus:ring-0 focus:border-indigo-500';
    $readonlyClasses =
        'bg-gray-50 border-gray-300 dark:bg-gray-800 dark:border-gray-700 text-gray-500 cursor-not-allowed';
    $mergedClasses = $baseClasses . ' ' . ($isReadonly ? $readonlyClasses : $activeClasses);

    $labelBaseClasses =
        'absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 start-2';
    $labelActiveClasses =
        'bg-white dark:bg-gray-900 peer-focus:px-2 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 cursor-text';
    $labelReadonlyClasses =
        'bg-gray-50 dark:bg-gray-800 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 cursor-not-allowed';
    $mergedLabelClasses = $labelBaseClasses . ' ' . ($isReadonly ? $labelReadonlyClasses : $labelActiveClasses);
@endphp

<div class="relative {{ $class }}">
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
        {{ $required ? 'required' : '' }} placeholder=" " {{ $isCurrency ? 'data-currency' : '' }}
        {{ $isReadonly ? 'readonly' : '' }} {{ $attributes->merge(['class' => $mergedClasses]) }} />
    <label for="{{ $id }}" class="{{ $mergedLabelClasses }}">{{ $label }}</label>
    @if ($showError)
        @error($name)
            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    @endif
</div>
