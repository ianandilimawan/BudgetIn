<div class="space-y-6">
    <!-- Category Name -->
    <div>
        <x-input-floating type="text" name="name" label="Nama Kategori" value="{{ old('name', $transactionCategory->name ?? '') }}" required="true" />
        @error('name')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Category Type -->
    <div>
        <x-select-floating name="type" label="Tipe Arus Kas" value="{{ old('type', $transactionCategory->type ?? 'expense') }}" :options="['expense' => 'Pengeluaran (Expense)', 'income' => 'Pemasukan (Income)']" required="true" />
        @error('type')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Visual Icon Picker -->
    @php
        $availableIcons = \App\Helpers\CategoryIconHelper::getIconDefinitions();
        $selectedIcon = old('icon', $transactionCategory->icon ?? 'tag');
    @endphp

    <div class="border border-zinc-200 dark:border-zinc-700/80 rounded-2xl p-5 bg-zinc-50/50 dark:bg-zinc-800/30">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <label class="block text-sm font-bold text-zinc-900 dark:text-white">Pilih Icon Visual Kategori</label>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Klik salah satu icon visual yang sesuai dengan jenis kategori keuangan ini.</p>
            </div>

            <!-- Search Icon Input -->
            <div class="relative w-full sm:w-64">
                <input type="text" id="iconSearchInput" placeholder="Cari icon (contoh: makan, gaji)..."
                    class="w-full text-xs px-3 py-2 pl-8 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                <svg class="w-4 h-4 text-zinc-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Hidden Input for Form Submission -->
        <input type="hidden" name="icon" id="categoryIconInput" value="{{ $selectedIcon }}">

        <!-- Icon Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5 max-h-72 overflow-y-auto p-1" id="iconGridContainer">
            @foreach ($availableIcons as $iconKey => $iconData)
                @php
                    $isSelected = ($selectedIcon === $iconKey);
                @endphp
                <button type="button"
                    class="icon-option-btn flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all duration-200 hover:scale-105 cursor-pointer {{ $isSelected ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 ring-2 ring-emerald-500/30' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
                    data-icon-key="{{ $iconKey }}"
                    data-keywords="{{ $iconData['keywords'] }} {{ $iconData['label'] }}"
                    onclick="selectCategoryIcon('{{ $iconKey }}', this)">
                    <div class="w-9 h-9 rounded-xl {{ $isSelected ? 'bg-emerald-500 text-white' : 'bg-zinc-100 dark:bg-zinc-700/80 text-zinc-600 dark:text-zinc-300' }} flex items-center justify-center mb-1.5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $iconData['svg'] !!}
                        </svg>
                    </div>
                    <span class="text-[11px] font-medium leading-tight truncate w-full">{{ $iconData['label'] }}</span>
                </button>
            @endforeach
        </div>

        <p id="noIconsFoundText" class="text-xs text-zinc-500 text-center py-4 hidden">Icon tidak ditemukan. Coba kata kunci lain.</p>
    </div>

    <!-- Is Active Toggle -->
    <div>
        <x-toggle name="is_active" label="Status Kategori Aktif" :checked="$transactionCategory->is_active ?? true" />
    </div>
</div>

<script>
    function selectCategoryIcon(iconKey, buttonElement) {
        document.getElementById('categoryIconInput').value = iconKey;

        // Reset all buttons styling
        document.querySelectorAll('.icon-option-btn').forEach(btn => {
            btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-950/40', 'text-emerald-700', 'dark:text-emerald-300', 'ring-2', 'ring-emerald-500/30');
            btn.classList.add('border-zinc-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-800', 'text-zinc-700', 'dark:text-zinc-300');

            const iconBox = btn.querySelector('div');
            if (iconBox) {
                iconBox.classList.remove('bg-emerald-500', 'text-white');
                iconBox.classList.add('bg-zinc-100', 'dark:bg-zinc-700/80', 'text-zinc-600', 'dark:text-zinc-300');
            }
        });

        // Set active styling on clicked button
        buttonElement.classList.remove('border-zinc-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-800', 'text-zinc-700', 'dark:text-zinc-300');
        buttonElement.classList.add('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-950/40', 'text-emerald-700', 'dark:text-emerald-300', 'ring-2', 'ring-emerald-500/30');

        const activeIconBox = buttonElement.querySelector('div');
        if (activeIconBox) {
            activeIconBox.classList.remove('bg-zinc-100', 'dark:bg-zinc-700/80', 'text-zinc-600', 'dark:text-zinc-300');
            activeIconBox.classList.add('bg-emerald-500', 'text-white');
        }
    }

    // Filter icons by search query
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('iconSearchInput');
        const iconButtons = document.querySelectorAll('.icon-option-btn');
        const noIconsFound = document.getElementById('noIconsFoundText');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let matchCount = 0;

                iconButtons.forEach(btn => {
                    const keywords = (btn.getAttribute('data-keywords') || '').toLowerCase();
                    if (query === '' || keywords.includes(query)) {
                        btn.style.display = 'flex';
                        matchCount++;
                    } else {
                        btn.style.display = 'none';
                    }
                });

                if (noIconsFound) {
                    noIconsFound.classList.toggle('hidden', matchCount > 0);
                }
            });
        }
    });
</script>

@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = true;
        $hasSlugField = false;
        $slugSourceField = 'name';
        $tagifyFields = array ();
        $textareaFields = array ();
        $selectFields = array ();
        $currencyFields = array ();
        $passwordFields = array ();
    @endphp
    @include('admin.partials.form-scripts')
@endpush