@once
    <!-- Form Component Libraries (bundled via Vite instead of CDN) -->
    @vite('resources/js/form-libs.js')
@endonce

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (isset($hasSlugField) && $hasSlugField && isset($slugSourceField) && $slugSourceField)
            // Auto-generate slug from title or name
            var sourceFieldId = '{{ $slugSourceField }}';
            var sourceInput = document.getElementById(sourceFieldId);
            var slugInput = document.getElementById('slug');
            var slugManuallyEdited = false;

            // Function to generate slug from text
            function generateSlug(text) {
                return text
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '') // Remove special characters
                    .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
                    .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
            }

            // Auto-generate slug when source field (title/name) changes
            if (sourceInput && slugInput) {
                // Track if user manually edits the slug field
                slugInput.addEventListener('keydown', function(e) {
                    // Detect if user is typing (not just navigation keys)
                    if (!e.ctrlKey && !e.metaKey && !e.altKey) {
                        // Allow: Backspace, Delete, Arrow keys, Tab, Enter
                        if (!['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                                'Tab', 'Enter'
                            ].includes(e.key)) {
                            slugManuallyEdited = true;
                        }
                    }
                });

                // Also track paste events
                slugInput.addEventListener('paste', function() {
                    slugManuallyEdited = true;
                });

                // Auto-generate slug when source field changes
                sourceInput.addEventListener('input', function() {
                    // Only auto-generate if slug hasn't been manually edited
                    if (!slugManuallyEdited) {
                        var sourceValue = sourceInput.value;
                        slugInput.value = generateSlug(sourceValue);
                    }
                });
            }
        @endif

        @if (isset($tagifyFields) && !empty($tagifyFields))
            // Tagify initialization
            @foreach ($tagifyFields as $fieldId)
                var {{ $fieldId }}Input = document.querySelector('#{{ $fieldId }}');
                if ({{ $fieldId }}Input) {
                    var {{ $fieldId }}Tagify = new Tagify({{ $fieldId }}Input, {
                        duplicates: false,
                        trim: true,
                        placeholder: 'Add tags...'
                    });

                    // Set border-color inline after Tagify initialization
                    setTimeout(function() {
                        var {{ $fieldId }}TagsElement = {{ $fieldId }}Input.closest('tags') ||
                            document.querySelector('tags[id="{{ $fieldId }}"]') || document
                            .querySelector('tags');
                        if ({{ $fieldId }}TagsElement) {
                            var isDark = document.documentElement.classList.contains('dark');
                            if (isDark) {
                                {{ $fieldId }}TagsElement.style.borderColor =
                                    'var(--color-gray-700, oklch(37.3% 0.034 259.733))';
                            } else {
                                {{ $fieldId }}TagsElement.style.borderColor =
                                    'rgb(229, 231, 235)';
                            }
                        }
                    }, 100);

                    // Update border-color on theme change
                    var {{ $fieldId }}Observer = new MutationObserver(function() {
                        var {{ $fieldId }}TagsElement = {{ $fieldId }}Input.closest(
                                'tags') || document.querySelector('tags[id="{{ $fieldId }}"]') ||
                            document.querySelector('tags');
                        if ({{ $fieldId }}TagsElement) {
                            var isDark = document.documentElement.classList.contains('dark');
                            if (isDark) {
                                {{ $fieldId }}TagsElement.style.borderColor =
                                    'var(--color-gray-700, oklch(37.3% 0.034 259.733))';
                            } else {
                                {{ $fieldId }}TagsElement.style.borderColor =
                                    'rgb(229, 231, 235)';
                            }
                        }
                    });
                    {{ $fieldId }}Observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }
            @endforeach
        @endif

        @if (isset($textareaFields) && !empty($textareaFields))
            // TinyMCE initialization function
            function initTinyMCE(selector) {
                var isDark = document.documentElement.classList.contains('dark');

                // Remove existing TinyMCE instance if any
                if (tinymce.get(selector.replace('#', ''))) {
                    tinymce.remove(selector.replace('#', ''));
                }

                tinymce.init({
                    selector: selector,
                    plugins: [
                        // Free plugins only
                        'anchor', 'autolink', 'charmap', 'code', 'codesample', 'directionality',
                        'emoticons', 'fullscreen', 'help', 'image', 'insertdatetime', 'link',
                        'lists', 'media', 'nonbreaking', 'pagebreak', 'preview', 'searchreplace',
                        'table', 'template', 'visualblocks', 'visualchars', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image media table | code codesample | charmap emoticons | preview fullscreen | removeformat',
                    height: 400,
                    menubar: false,
                    branding: false,
                    promotion: false,
                    skin: isDark ? 'oxide-dark' : 'oxide',
                    content_css: isDark ? 'dark' : 'default',
                    content_style: 'body { font-family: "Instrument Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; ' +
                        (isDark ? 'background-color: #1f2937; color: #fff;' : '') + ' }',
                    placeholder: 'Start typing...',
                    setup: function(editor) {
                        editor.on('init', function() {
                            // Content is already set from textarea value
                            // Force set border after TinyMCE is fully initialized
                            function setTinyMCEBorder() {
                                var tinymceElement = editor.getContainer();
                                if (tinymceElement) {
                                    var isDark = document.documentElement.classList
                                        .contains('dark');
                                    var borderColor = isDark ?
                                        'var(--color-gray-700, oklch(37.3% 0.034 259.733))' :
                                        'rgb(229, 231, 235)';
                                    var borderValue = isDark ?
                                        'oklch(37.3% 0.034 259.733)' : 'rgb(229, 231, 235)';
                                    tinymceElement.style.border = '2px solid ' +
                                        borderValue;
                                    tinymceElement.style.borderColor = borderColor;
                                    tinymceElement.style.borderRadius = '0.5rem';
                                    tinymceElement.style.setProperty('border',
                                        '2px solid ' + borderValue, 'important');
                                    tinymceElement.style.setProperty('border-color',
                                        borderColor, 'important');
                                    tinymceElement.style.setProperty('border-radius',
                                        '0.5rem', 'important');
                                }
                            }

                            // Try multiple times to ensure border is set
                            setTinyMCEBorder();
                            setTimeout(setTinyMCEBorder, 100);
                            setTimeout(setTinyMCEBorder, 300);
                            setTimeout(setTinyMCEBorder, 500);
                        });

                        // Also set border when editor is fully loaded
                        editor.on('loadedmetadata', function() {
                            setTimeout(function() {
                                var tinymceElement = editor.getContainer();
                                if (tinymceElement) {
                                    var isDark = document.documentElement.classList
                                        .contains('dark');
                                    var borderColor = isDark ?
                                        'var(--color-gray-700, oklch(37.3% 0.034 259.733))' :
                                        'rgb(229, 231, 235)';
                                    var borderValue = isDark ?
                                        'oklch(37.3% 0.034 259.733)' :
                                        'rgb(229, 231, 235)';
                                    tinymceElement.style.setProperty('border',
                                        '2px solid ' + borderValue, 'important');
                                    tinymceElement.style.setProperty('border-color',
                                        borderColor, 'important');
                                    tinymceElement.style.setProperty(
                                        'border-radius', '0.5rem', 'important');
                                }
                            }, 100);
                        });
                    }
                });
            }

            @foreach ($textareaFields as $fieldId)
                // Initialize TinyMCE for {{ $fieldId }}
                if (document.getElementById('{{ $fieldId }}')) {
                    initTinyMCE('#{{ $fieldId }}');
                }
            @endforeach
        @endif

        // TomSelect initialization
        window.tomSelectInstances = window.tomSelectInstances || {};

        function initSelect2() {
            if (typeof TomSelect === 'undefined') {
                console.warn('TomSelect is not loaded. Skipping initialization.');
                return;
            }

            // Find all select elements with 'select2' class
            var selects = document.querySelectorAll('select.select2');
            selects.forEach(function(select) {
                // Skip if already initialized
                if (select.tomselect) {
                    return;
                }

                // Get placeholder from first empty option or generate from field name
                var placeholder = '';
                var firstOption = select.querySelector('option[value=""]');
                if (firstOption) {
                    placeholder = firstOption.textContent.trim();
                }

                // If no placeholder found, generate from field name
                if (!placeholder) {
                    var fieldId = select.id || select.name || '';
                    if (fieldId) {
                        // Convert field_id to "Field Id"
                        placeholder = 'Select ' + fieldId
                            .replace(/_/g, ' ')
                            .replace(/\b\w/g, function(l) {
                                return l.toUpperCase();
                            });
                    } else {
                        placeholder = 'Select Option';
                    }
                }

                // Determine plugins based on whether it's a multiple select
                var plugins = ['clear_button'];
                if (select.hasAttribute('multiple')) {
                    plugins.push('remove_button');
                }

                // Initialize TomSelect with default options
                window.tomSelectInstances[select.id || select.name] = new TomSelect(select, {
                    placeholder: placeholder,
                    plugins: plugins
                });
            });

            // Also initialize fields from $selectFields array (for backward compatibility)
            @if (isset($selectFields) && !empty($selectFields))
                @foreach ($selectFields as $fieldId)
                    @php
                        $fieldLabel = ucfirst(str_replace('_', ' ', $fieldId));
                    @endphp
                    var selectEl_{{ $fieldId }} = document.getElementById('{{ $fieldId }}');
                    if (selectEl_{{ $fieldId }} && !selectEl_{{ $fieldId }}.tomselect) {
                        var plugins = ['clear_button'];
                        if (selectEl_{{ $fieldId }}.hasAttribute('multiple')) {
                            plugins.push('remove_button');
                        }

                        window.tomSelectInstances['{{ $fieldId }}'] = new TomSelect(
                            selectEl_{{ $fieldId }}, {
                                placeholder: 'Select {{ $fieldLabel }}',
                                plugins: plugins
                            });
                    }
                @endforeach
            @endif
        }

        // Initialize TomSelect on page load
        initSelect2();

        @if (isset($currencyFields) && !empty($currencyFields))
            // Initialize currency formatting for inputs with data-currency attribute
            function initCurrencyFormatting() {
                document.querySelectorAll('input[data-currency]').forEach(function(input) {
                    // Check if AutoNumeric is already initialized on this element
                    var existingInstance = AutoNumeric.getAutoNumericElement(input);
                    if (!existingInstance) {
                        // Get current value and clean it
                        var currentValue = input.value || '';
                        // Remove currency formatting characters (commas, dots, etc.) to get raw number
                        var rawValue = currentValue.toString().replace(/[^\d.-]/g, '');

                        // Initialize AutoNumeric with Indonesian Rupiah format
                        var autoNumericInstance = new AutoNumeric(input, {
                            digitGroupSeparator: '.',
                            decimalCharacter: ',',
                            decimalPlaces: 0,
                            currencySymbol: 'Rp ',
                            currencySymbolPlacement: 'p',
                            allowDecimalPadding: false,
                            minimumValue: '-999999999999',
                            maximumValue: '999999999999',
                            formatOnPageLoad: true,
                            unformatOnSubmit: true,
                            modifyValueOnWheel: false,
                            emptyInputBehavior: 'zero'
                        });

                        // Set the value if it exists, otherwise set to 0
                        if (rawValue && rawValue !== '') {
                            autoNumericInstance.set(parseFloat(rawValue) || 0);
                        } else {
                            autoNumericInstance.set(0);
                        }
                    }
                });
            }

            // Initialize currency formatting on page load
            if (typeof AutoNumeric !== 'undefined') {
                initCurrencyFormatting();
            } else {
                // Wait for AutoNumeric to load
                var checkAutoNumeric = setInterval(function() {
                    if (typeof AutoNumeric !== 'undefined') {
                        clearInterval(checkAutoNumeric);
                        initCurrencyFormatting();
                    }
                }, 100);

                // Timeout after 5 seconds
                setTimeout(function() {
                    clearInterval(checkAutoNumeric);
                }, 5000);
            }

            // Ensure currency values are unformatted before form submission
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    document.querySelectorAll('input[data-currency]').forEach(function(input) {
                        var autoNumericInstance = AutoNumeric.getAutoNumericElement(
                            input);
                        if (autoNumericInstance) {
                            // Get unformatted value and set it back to the input
                            var unformattedValue = autoNumericInstance.getNumber();
                            input.value = unformattedValue || '';
                        }
                    });
                });
            });
        @endif

        @if (isset($passwordFields) && !empty($passwordFields))
            // Auto-enhance password fields with Show/Hide toggle and Strength Meter
            @foreach ($passwordFields as $fieldId)
                (function() {
                    var passwordInput = document.getElementById('{{ $fieldId }}');
                    if (!passwordInput) return;

                    // Ensure parent wrapper has relative positioning
                    var parentContainer = passwordInput.parentElement;
                    if (parentContainer && !parentContainer.classList.contains('relative')) {
                        parentContainer.classList.add('relative');
                    }

                    // Append Toggle Eye Button if not already added
                    if (!parentContainer.querySelector('.password-toggle-btn')) {
                        var toggleBtn = document.createElement('button');
                        toggleBtn.type = 'button';
                        toggleBtn.className =
                            'password-toggle-btn absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-white transition z-20 focus:outline-none';
                        toggleBtn.innerHTML = '<i class="fa-solid fa-eye text-xs"></i>';
                        toggleBtn.setAttribute('tabindex', '-1');

                        toggleBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            var currentType = passwordInput.getAttribute('type');
                            if (currentType === 'password') {
                                passwordInput.setAttribute('type', 'text');
                                toggleBtn.innerHTML =
                                    '<i class="fa-solid fa-eye-slash text-xs text-indigo-500"></i>';
                            } else {
                                passwordInput.setAttribute('type', 'password');
                                toggleBtn.innerHTML = '<i class="fa-solid fa-eye text-xs"></i>';
                            }
                        });
                        parentContainer.appendChild(toggleBtn);
                    }

                    // Append Password Strength Meter Bar Container
                    var outerWrapper = parentContainer.parentElement;
                    if (outerWrapper && !outerWrapper.querySelector('.password-strength-container')) {
                        var strengthContainer = document.createElement('div');
                        strengthContainer.className =
                            'password-strength-container hidden space-y-1 pt-1.5 px-1';
                        strengthContainer.innerHTML = `
                        <div class="flex items-center justify-between text-[11px] font-semibold">
                            <span class="text-gray-500 dark:text-gray-400">Kekuatan Password:</span>
                            <span class="strength-label text-rose-500">Sangat Lemah</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden flex gap-1">
                            <div class="bar-1 h-full transition-all duration-300 rounded-full flex-1 bg-transparent"></div>
                            <div class="bar-2 h-full transition-all duration-300 rounded-full flex-1 bg-transparent"></div>
                            <div class="bar-3 h-full transition-all duration-300 rounded-full flex-1 bg-transparent"></div>
                            <div class="bar-4 h-full transition-all duration-300 rounded-full flex-1 bg-transparent"></div>
                        </div>
                    `;
                        outerWrapper.appendChild(strengthContainer);

                        var labelEl = strengthContainer.querySelector('.strength-label');
                        var bars = [
                            strengthContainer.querySelector('.bar-1'),
                            strengthContainer.querySelector('.bar-2'),
                            strengthContainer.querySelector('.bar-3'),
                            strengthContainer.querySelector('.bar-4')
                        ];

                        passwordInput.addEventListener('input', function() {
                            var val = passwordInput.value;
                            if (!val || val.length === 0) {
                                strengthContainer.classList.add('hidden');
                                return;
                            }

                            strengthContainer.classList.remove('hidden');

                            var score = 0;
                            if (val.length >= 6) score += 1;
                            if (val.length >= 10) score += 1;
                            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score += 1;
                            if (/[0-9]/.test(val)) score += 1;
                            if (/[^A-Za-z0-9]/.test(val)) score += 1;
                            score = Math.min(4, Math.max(1, score));

                            var labels = ['', 'Sangat Lemah', 'Lemah', 'Sedang', 'Kuat'];
                            var colors = ['', 'bg-rose-500', 'bg-rose-500', 'bg-amber-500',
                                'bg-emerald-500'
                            ];
                            var textColors = ['', 'text-rose-500', 'text-rose-500',
                                'text-amber-500', 'text-emerald-500'
                            ];

                            labelEl.textContent = labels[score];
                            labelEl.className = 'strength-label ' + textColors[score];

                            bars.forEach(function(bar, idx) {
                                if (idx < score) {
                                    bar.className =
                                        'h-full transition-all duration-300 rounded-full flex-1 ' +
                                        colors[score];
                                } else {
                                    bar.className =
                                        'h-full transition-all duration-300 rounded-full flex-1 bg-transparent';
                                }
                            });
                        });
                    }
                })();
            @endforeach
        @endif

        @if ((isset($textareaFields) && !empty($textareaFields)) || (isset($selectFields) && !empty($selectFields)))
            // Watch for theme changes and reinitialize components
            var observer = new MutationObserver(function() {
                var isDark = document.documentElement.classList.contains('dark');

                @if (isset($textareaFields) && !empty($textareaFields))
                    // Force update TinyMCE border
                    function updateTinyMCEBorder(editorId) {
                        var editor = tinymce.get(editorId);
                        if (editor) {
                            var container = editor.getContainer();
                            if (container) {
                                var borderColor = isDark ? 'oklch(37.3% 0.034 259.733)' :
                                    'rgb(229, 231, 235)';
                                container.style.setProperty('border', '2px solid ' + borderColor,
                                    'important');
                                container.style.setProperty('border-color', isDark ?
                                    'var(--color-gray-700, oklch(37.3% 0.034 259.733))' :
                                    'rgb(229, 231, 235)', 'important');
                                container.style.setProperty('border-radius', '0.5rem', 'important');
                            }
                        }
                    }

                    @foreach ($textareaFields as $fieldId)
                        updateTinyMCEBorder('{{ $fieldId }}');
                    @endforeach
                @endif
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        @endif
    });
</script>
