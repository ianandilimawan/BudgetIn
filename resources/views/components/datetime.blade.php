@props(['name', 'label' => null, 'value' => null])

@php
    // Format the value to YYYY-MM-DDThh:mm for datetime-local input
    $formattedValue = '';
    $currentValue = old($name, $value);
    
    if ($currentValue) {
        try {
            $formattedValue = \Carbon\Carbon::parse($currentValue)->format('Y-m-d\TH:i');
        } catch (\Exception $e) {
            $formattedValue = $currentValue;
        }
    }
@endphp

<div>
    @if ($label)
        <label for="{{ $name }}"
            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ $label }}</label>
    @endif
    <div>
        <input type="datetime-local" name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'block w-full rounded-lg border-0 dark:border-2 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-md dark:shadow-md focus:ring-2 focus:ring-blue-500 px-4 py-4 transition-colors']) }}
            value="{{ $formattedValue }}">
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
