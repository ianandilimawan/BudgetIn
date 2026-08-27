@props([
    'name',
    'label' => '',
    'accept' => 'image/*,.webp,.gif,.svg,image/svg+xml',
    'defaultFile' => null,
    'isAvatar' => false,
    'hint' => 'Format: JPG, PNG, WebP (Rekomendasi resolusi 800x800 px, maks. 2 MB agar efisien & cepat).',
])

@once
    @push('scripts')
        @vite('resources/js/filepond-lib.js')
    @endpush
@endonce

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}"
            class="block text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400 mb-1">
            {{ $label }}
        </label>
    @endif
    @if ($hint)
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-2 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $hint }}</span>
        </p>
    @endif

    <div x-data="{ pond: null }" x-init="if (typeof FilePondPluginImagePreview !== 'undefined') FilePond.registerPlugin(FilePondPluginImagePreview);
    if (typeof FilePondPluginImageCrop !== 'undefined') FilePond.registerPlugin(FilePondPluginImageCrop);
    
    pond = FilePond.create($refs.input, {
        storeAsFile: true,
        allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
        server: null,
        credits: false,
        imagePreviewHeight: {{ $isAvatar ? 150 : 200 }},
        {!! $isAvatar
            ? "
                stylePanelLayout: 'compact circle',
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',
                imageCropAspectRatio: '1:1',
                "
            : '' !!}
    });
    
    @if ($defaultFile) pond.addFile('{{ $defaultFile }}'); @endif" class="filepond-wrapper {{ $isAvatar ? 'w-40' : '' }}">
        <input type="file" x-ref="input" name="{{ $name }}" id="{{ $name }}"
            accept="{{ $accept }}" {{ $attributes }}>
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

<style>
    /* Custom FilePond styles for modern UI */
    .filepond--root {
        background-color: #f9fafb;
        /* bg-gray-50 */
        border: 2px dashed #e5e7eb;
        /* border-gray-200 */
        border-radius: 0.75rem;
        /* rounded-xl */
        transition: all 0.2s ease;
        margin-bottom: 0;
        font-family: inherit;
        overflow: hidden;
    }

    .filepond--root:hover {
        border-color: #d1d5db;
        /* border-gray-300 */
    }

    .dark .filepond--root {
        background-color: rgba(31, 41, 55, 0.8);
        /* bg-gray-800/80 */
        border: 2px dashed #374151;
        /* border-gray-700 */
    }

    .dark .filepond--root:hover {
        border-color: #4b5563;
        /* border-gray-600 */
    }

    /* Make internal panel transparent so our root styling shows through */
    .filepond--panel-root {
        background-color: transparent !important;
        border: none !important;
    }

    .filepond--root[data-style-panel-layout~='circle'] {
        border-radius: 50% !important;
    }

    .filepond--drop-label {
        color: #6b7280 !important;
        /* gray-500 */
    }

    .dark .filepond--drop-label {
        color: #9ca3af !important;
        /* gray-400 */
    }
</style>
