<style>
    /* Placeholder color for dark mode */
    .dark input::placeholder,
    .dark textarea::placeholder {
        color: #9ca3af !important;
    }

    /* Tagify Styles - Match meta title exactly */
    tagify {
        border: 0 !important;
        border-radius: 0.5rem !important;
        padding: 0 !important;
        min-height: 60px !important;
        height: 60px !important;
        width: 100% !important;
        max-width: 100% !important;
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        /* shadow-md - same as meta title */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1) !important;
        box-sizing: border-box !important;
        flex: 1 1 100% !important;
    }

    /* Ensure tags are vertically centered - match meta title height exactly */
    tagify .tagify__tags {
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        padding: 16px !important;
        min-height: 60px !important;
        height: 100% !important;
        width: 100% !important;
        gap: 6px !important;
        box-sizing: border-box !important;
    }

    tagify .tagify__tag {
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
        height: auto !important;
        line-height: 1.5 !important;
        vertical-align: middle !important;
    }

    /* Ensure tagify input wrapper is centered */
    tagify .tagify__input {
        display: inline-flex !important;
        align-items: center !important;
        vertical-align: middle !important;
        min-height: 28px !important;
        line-height: 1.5 !important;
    }

    .dark tagify {
        border: 2px solid oklch(37.3% 0.034 259.733) !important;
        border-color: var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
        background-color: #111827 !important;
    }

    /* Light mode - no border like meta title */
    tagify:not(.dark) {
        border: 0 !important;
    }

    tagify:focus-within {
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
    }

    .dark tagify:focus-within {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }

    /* Tagify Input Styling - Override py-4 padding */
    tagify .tagify__input {
        padding: 0 !important;
        margin: 0 !important;
        min-height: auto !important;
        height: auto !important;
        line-height: 1.5 !important;
        color: #111827 !important;
        display: inline-flex !important;
        align-items: center !important;
        flex: 1 !important;
    }

    /* Override input padding when inside tagify */
    tagify input.tagify__input {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* Style input before tagify initializes - match tagify styling */
    input#meta_keywords:not([data-tagify]) {
        height: 60px !important;
        min-height: 60px !important;
        display: flex !important;
        align-items: center !important;
    }

    .dark .tagify__input {
        color: #fff !important;
    }

    /* Tagify Placeholder - Improve readability */
    tagify[data-placeholder]:empty::before {
        color: #6b7280 !important;
        opacity: 1 !important;
        font-size: 14px !important;
        padding: 0 !important;
        line-height: 1.5 !important;
        display: flex !important;
        align-items: center !important;
    }

    .dark tagify[data-placeholder]:empty::before {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    /* Tagify Empty Placeholder - Match text color */
    .tagify--empty .tagify__input::before {
        color: #111827 !important;
        opacity: 1 !important;
    }

    .dark .tagify--empty .tagify__input::before {
        color: #fff !important;
        opacity: 1 !important;
    }

    .tagify__input::placeholder {
        color: #6b7280 !important;
        opacity: 1 !important;
    }

    .dark .tagify__input::placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    /* Reset TomSelect wrapper to prevent Tailwind background propagation */
    .ts-wrapper {
        background: transparent !important;
        background-color: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    /* TomSelect Control Styling - Match input-floating.blade.php exactly */
    .ts-wrapper .ts-control,
    .ts-wrapper .ts-control > input,
    .ts-wrapper.single .ts-control,
    .ts-wrapper.full .ts-control,
    .ts-wrapper.focus .ts-control,
    .ts-wrapper.input-active .ts-control {
        background: transparent !important;
        background-color: transparent !important;
    }

    .ts-wrapper .ts-control {
        border: 1px solid rgb(209, 213, 219) !important; /* border-gray-300 */
        border-radius: 0.75rem !important; /* rounded-xl */
        min-height: 58px !important; /* Match floating input height */
        padding: 16px !important;
        box-shadow: none !important;
        color: #111827 !important; /* text-gray-900 */
        transition: border-color 0.15s ease-in-out;
    }



    .dark .ts-wrapper .ts-control,
    .dark .ts-wrapper.single .ts-control,
    .dark .ts-wrapper.full .ts-control {
        border-color: #374151 !important; /* border-gray-700 */
        color: #ffffff !important;
        background: transparent !important;
        background-color: transparent !important;
    }
    
    .dark .ts-wrapper .ts-control .item, .dark .ts-wrapper .ts-control input {
        color: #ffffff !important;
    }

    /* Focus state - Match input-floating (border-indigo-500) */
    .ts-wrapper.focus .ts-control,
    .ts-wrapper.input-active .ts-control {
        border-color: #6366f1 !important; /* indigo-500 */
        box-shadow: none !important;
        outline: none !important;
    }

    .dark .ts-wrapper.focus .ts-control,
    .dark .ts-wrapper.input-active .ts-control {
        border-color: #6366f1 !important;
    }

    /* TomSelect Dropdown */
    .ts-dropdown {
        border: 1px solid rgb(209, 213, 219) !important;
        border-radius: 0.75rem !important;
        --tw-shadow: 0 10px 15px -3px var(--tw-shadow-color, rgb(0 0 0 / 0.1)), 0 4px 6px -2px var(--tw-shadow-color, rgb(0 0 0 / 0.05)) !important;
        box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow) !important;
        margin-top: 4px;
        background-color: #ffffff !important;
        z-index: 9999 !important; /* Force dropdown to be above floating labels */
    }

    .dark .ts-dropdown {
        background-color: #1f2937 !important; /* bg-gray-800 */
        border-color: #374151 !important; /* border-gray-700 */
        color: #ffffff !important;
    }

    .ts-dropdown .option {
        padding: 12px 16px !important;
    }

    .dark .ts-dropdown .option {
        background-color: #1f2937 !important;
        color: #ffffff !important;
    }

    .dark .ts-dropdown .active {
        background-color: #374151 !important; /* bg-gray-700 */
        color: #ffffff !important;
    }

    /* TinyMCE Border and Placeholder Styles - Match Tagify exactly */
    .tox-tinymce {
        border: 2px solid rgb(229, 231, 235) !important;
        border-color: rgb(229, 231, 235) !important;
        border-radius: 0.5rem !important;
        --tw-shadow: 0 4px 6px -1px var(--tw-shadow-color, rgb(0 0 0 / 0.1)), 0 2px 4px -2px var(--tw-shadow-color, rgb(0 0 0 / 0.1)) !important;
        box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow) !important;
    }

    .dark .tox-tinymce {
        border: 2px solid var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
        border-color: var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
    }

    .tox-tinymce.tox-tinymce--focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }

    .tox .tox-edit-area__iframe {
        background-color: #fff !important;
    }

    .dark .tox .tox-edit-area__iframe {
        background-color: #1f2937 !important;
    }

    /* Placeholder color for TinyMCE */
    .tox .tox-edit-area p[data-mce-placeholder] {
        color: #9ca3af !important;
    }

    .dark .tox .tox-edit-area p[data-mce-placeholder] {
        color: #9ca3af !important;
    }

    /* Remove duplicate caret in TomSelect */
    .ts-wrapper.single .ts-control::after {
        display: none !important;
    }

    /* CRITICAL: Force remove global input styling from TomSelect inner input */
    /* Must be at the very bottom to win against global input[type="text"] rules */
    #adminHtml .ts-wrapper .ts-control > input,
    #adminHtml.dark .ts-wrapper .ts-control > input,
    #adminHtml .ts-wrapper .ts-control > input:focus,
    #adminHtml.dark .ts-wrapper .ts-control > input:focus {
        border: none !important;
        border-width: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
        outline: none !important;
        background: transparent !important;
        min-height: 0 !important;
        line-height: inherit !important;
    }

    /* Fix select double arrows: ensure appearance-none completely removes native browser arrows */
    select.appearance-none,
    select[class*="appearance-none"] {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        background-image: none !important;
    }

    select::-ms-expand {
        display: none !important;
    }

    /* Prevent TomSelect from adding duplicate carets over custom SVG icons */
    .ts-wrapper.single .ts-control::after {
        display: none !important;
    }
</style>
