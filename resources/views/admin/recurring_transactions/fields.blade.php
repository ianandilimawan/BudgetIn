<div x-data="{
    trxType: '{{ old('type', $recurring->type ?? 'expense') }}',
    freq: '{{ old('frequency', $recurring->frequency ?? 'monthly') }}',
    setType(t) {
        this.trxType = t;
    }
}" class="space-y-4 sm:space-y-5">

    <!-- Name of schedule -->
    <div>
        <label for="name" class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-gray-700 dark:text-gray-300 mb-1">
            Nama Jadwal <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="name" id="name" value="{{ old('name', $recurring->name ?? '') }}" required
               class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs sm:text-sm font-medium placeholder:text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
               placeholder="Contoh: Langganan WiFi, Sewa Kost, Gaji" />
        @error('name')
            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tipe Transaksi Switcher -->
    <div>
        <label class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1">
            Tipe Arus Kas <span class="text-rose-500">*</span>
        </label>
        <div class="grid grid-cols-3 gap-1 p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-xs font-semibold w-full sm:max-w-md">
            <button type="button" 
                    @click="setType('expense')"
                    :class="trxType === 'expense' ? 'bg-rose-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="px-2 py-2 rounded-lg transition-all flex items-center justify-center gap-1 sm:gap-1.5 font-bold cursor-pointer text-center">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                <span class="truncate">Pengeluaran</span>
            </button>
            <button type="button" 
                    @click="setType('income')"
                    :class="trxType === 'income' ? 'bg-emerald-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="px-2 py-2 rounded-lg transition-all flex items-center justify-center gap-1 sm:gap-1.5 font-bold cursor-pointer text-center">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                <span class="truncate">Pemasukan</span>
            </button>
            <button type="button" 
                    @click="setType('transfer')"
                    :class="trxType === 'transfer' ? 'bg-indigo-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="px-2 py-2 rounded-lg transition-all flex items-center justify-center gap-1 sm:gap-1.5 font-bold cursor-pointer text-center">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="inline sm:hidden">Transfer</span>
                <span class="hidden sm:inline">Transfer</span>
            </button>
        </div>
        <input type="hidden" name="type" :value="trxType">
    </div>

    <!-- Kategori (Expense / Income) -->
    <div x-show="trxType !== 'transfer'">
        <div x-show="trxType === 'expense'">
            <label class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-rose-600 dark:text-rose-400 mb-1">
                Kategori Pengeluaran <span class="text-rose-500">*</span>
            </label>
            <select name="category_id" class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500" :disabled="trxType !== 'expense'">
                <option value="">Pilih Kategori Pengeluaran...</option>
                @foreach($expenseCategories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $recurring->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div x-show="trxType === 'income'" style="display: none;">
            <label class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                Kategori Pemasukan <span class="text-emerald-500">*</span>
            </label>
            <select name="category_id" class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" :disabled="trxType !== 'income'">
                <option value="">Pilih Kategori Pemasukan...</option>
                @foreach($incomeCategories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $recurring->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Dompet / Akun Asal & Tujuan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div>
            <label class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1" x-text="trxType === 'transfer' ? 'Dari Akun Asal *' : (trxType === 'expense' ? 'Dari Dompet / Bank *' : 'Masuk Ke Dompet / Bank *')">
                Akun / Dompet
            </label>
            <select name="account_id" class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" required>
                @foreach($cashAccounts as $acc)
                    <option value="{{ $acc->id }}" {{ (old('account_id', $recurring->account_id ?? '') == $acc->id) ? 'selected' : '' }}>
                        {{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div x-show="trxType === 'transfer'" style="display: none;">
            <label class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                Ke Akun Tujuan <span class="text-emerald-500">*</span>
            </label>
            <select name="to_account_id" class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" :disabled="trxType !== 'transfer'">
                <option value="">Pilih Akun Tujuan...</option>
                @foreach($cashAccounts as $acc)
                    <option value="{{ $acc->id }}" {{ (old('to_account_id', $recurring->to_account_id ?? '') == $acc->id) ? 'selected' : '' }}>
                        {{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Nominal -->
    <div>
        <label for="amount" class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-gray-700 dark:text-gray-300 mb-1">
            Nominal (Rp) <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="amount" id="amount" value="{{ old('amount', $recurring->amount ?? '') }}" data-currency required
               class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs sm:text-sm font-bold placeholder:text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
               placeholder="Rp 0" />
        @error('amount')
            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Frekuensi & Tanggal Eksekusi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-3 sm:p-4 bg-zinc-50/50 dark:bg-zinc-800/30">
        <div>
            <label class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                Frekuensi Berulang <span class="text-rose-500">*</span>
            </label>
            <select name="frequency" x-model="freq" class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer">
                <option value="monthly">Bulanan (Setiap Bulan)</option>
                <option value="daily">Harian (Setiap Hari)</option>
                <option value="weekly">Mingguan</option>
                <option value="yearly">Tahunan</option>
            </select>
        </div>

        <div x-show="freq === 'monthly'">
            <label class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1">
                Tanggal Eksekusi (Tiap Tgl) <span class="text-rose-500">*</span>
            </label>
            <select name="day_of_month" class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer">
                @for ($d = 1; $d <= 31; $d++)
                    <option value="{{ $d }}" {{ old('day_of_month', $recurring->day_of_month ?? 1) == $d ? 'selected' : '' }}>
                        Tanggal {{ $d }} setiap bulan
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Periode Aktif -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div>
            <label for="start_date" class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-gray-700 dark:text-gray-300 mb-1">
                Mulai Berlaku <span class="text-rose-500">*</span>
            </label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', isset($recurring->start_date) && $recurring->start_date ? $recurring->start_date->format('Y-m-d') : date('Y-m-d')) }}" required
                   class="w-full max-w-full min-w-0 box-border h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition cursor-pointer" />
        </div>
        <div>
            <label for="end_date" class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-gray-700 dark:text-gray-300 mb-1">
                Berakhir Pada <span class="text-zinc-400 font-normal normal-case">(Opsional)</span>
            </label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', isset($recurring->end_date) && $recurring->end_date ? $recurring->end_date->format('Y-m-d') : '') }}"
                   class="w-full max-w-full min-w-0 box-border h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition cursor-pointer" />
        </div>
    </div>

    <!-- Catatan -->
    <div>
        <label for="recurring_note" class="block text-[11px] sm:text-xs uppercase tracking-wider font-bold text-gray-700 dark:text-gray-300 mb-1">
            Catatan Tambahan <span class="text-zinc-400 font-normal normal-case">(Opsional)</span>
        </label>
        <input type="text" name="note" id="recurring_note" value="{{ old('note', $recurring->note ?? '') }}"
               class="w-full h-[40px] sm:h-[42px] px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs sm:text-sm font-medium placeholder:text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
               placeholder="Catatan tambahan (opsional)" />
    </div>

    <!-- Is Active -->
    <div class="pt-1">
        <x-toggle name="is_active" label="Status Jadwal Aktif" :checked="$recurring->is_active ?? true" />
    </div>
</div>

@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $currencyFields = ['amount'];
    @endphp
    @include('admin.partials.form-scripts')
@endpush
