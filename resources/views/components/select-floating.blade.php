@props([
    'name',
    'id' => null,
    'label',
    'value' => null,
    'options' => [],
    'required' => false,
    'class' => '',
    'readonly' => false,
])

@php
    $id = $id ?? $name;
    $isReadonly = $readonly || $attributes->has('readonly') || $attributes->has('disabled');

    // Select specific styling
    $baseClasses =
        'block px-4 pb-2.5 pt-4.5 w-full text-base text-gray-900 rounded-xl border appearance-none dark:text-white peer transition-colors focus:outline-none focus:ring-0';
    $activeClasses =
        'bg-transparent border-gray-300 dark:border-gray-700 focus:border-indigo-500 dark:focus:border-indigo-500 cursor-pointer';
    $readonlyClasses =
        'bg-gray-50 border-gray-300 dark:bg-gray-800 dark:border-gray-700 text-gray-500 cursor-not-allowed';
    $mergedClasses = $baseClasses . ' ' . ($isReadonly ? $readonlyClasses : $activeClasses);

    // Label is always floating for select (no placeholder-shown mechanics needed because select always has a value or shows selected option)
    $labelBaseClasses =
        'absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 start-2 pointer-events-none';
    $labelActiveClasses = 'bg-white dark:bg-gray-900 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-500';
    $labelReadonlyClasses = 'bg-gray-50 dark:bg-gray-800';
    $mergedLabelClasses = $labelBaseClasses . ' ' . ($isReadonly ? $labelReadonlyClasses : $labelActiveClasses);
@endphp

<div class="relative {{ $class }}">
    <select name="{{ $name }}" id="{{ $id }}" {{ $required ? 'required' : '' }}
        {{ $isReadonly ? 'disabled' : '' }} {{ $attributes->merge(['class' => $mergedClasses]) }}>
        <option value="" disabled {{ is_null($value) || $value === '' ? 'selected' : '' }}>Select
            {{ $label }}</option>
        @foreach ($options as $key => $optionLabel)
            <option value="{{ $key }}" {{ (string) old($name, $value) === (string) $key ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    <!-- Custom dropdown arrow since we use appearance-none -->
    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none z-10">
        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
        </svg>
    </div>

    <label for="{{ $id }}" class="{{ $mergedLabelClasses }}">{{ $label }}</label>

    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
