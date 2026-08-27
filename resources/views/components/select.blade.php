@props(['name', 'label' => null, 'value' => null, 'options' => [], 'multiple' => false])

<div>
    @if ($label)
        <label for="{{ $name }}"
            class="block text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400 mb-2">{{ $label }}</label>
    @endif
    <div>
        <select name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="{{ $name }}"
            {{ $multiple ? 'multiple' : '' }} {{ $attributes->merge(['class' => 'block w-full appearance-none']) }}>
            @if (!$multiple)
                <option value="">Select {{ $label }}</option>
            @endif
            @foreach ($options as $key => $optionLabel)
                <option value="{{ $key }}"
                    {{ (is_array(old($name, $value)) ? in_array($key, old($name, $value)) : old($name, $value) == $key) ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
            {{ $slot }}
        </select>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
