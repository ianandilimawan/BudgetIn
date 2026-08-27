<div x-data="{
    trxType: '{{ old('type', $recurring->type ?? 'expense') }}',
    freq: '{{ old('frequency', $recurring->frequency ?? 'monthly') }}',
    setType(t) {
        this.trxType = t;
    }
}" class="space-y-4 sm:space-y-5">

    <!-- Name of schedule -->
    <div>
        <x-input-floating type="text" name="name" label="Nama Jadwal (contoh: Langganan WiFi, Sewa Kost, Gaji)" value="{{ old('name', $recurring->name ?? '') }}" required="true" />
        @error('name')
            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tipe Transaksi Switcher -->
    <div>
        <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
            Tipe Arus Kas <span class="text-rose-500">*</span>
        </label>
        <div class="inline-flex p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-xs font-semibold w-full sm:w-auto">
            <button type="button" 
                    @click="setType('expense')"
                    :class="trxType === 'expense' ? 'bg-rose-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 sm:flex-initial px-3 py-1.5 rounded-lg transition-all flex items-center justify-center gap-1 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                <span>Pengeluaran</span>
            </button>
            <button type="button" 
                    @click="setType('income')"
                    :class="trxType === 'income' ? 'bg-emerald-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 sm:flex-initial px-3 py-1.5 rounded-lg transition-all flex items-center justify-center gap-1 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                <span>Pemasukan</span>
            </button>
            <button type="button" 
                    @click="setType('transfer')"
                    :class="trxType === 'transfer' ? 'bg-indigo-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="flex-1 sm:flex-initial px-3 py-1.5 rounded-lg transition-all flex items-center justify-center gap-1 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span>Transfer / Pindah</span>
            </button>
        </div>
        <input type="hidden" name="type" :value="trxType">
    </div>

    <!-- Kategori (Expense / Income) -->
    <div x-show="trxType !== 'transfer'">
        <div x-show="trxType === 'expense'">
            <label class="block text-xs uppercase tracking-wider font-bold text-rose-600 dark:text-rose-400 mb-1.5">
                Kategori Pengeluaran <span class="text-rose-500">*</span>
            </label>
            <select name="category_id" class="w-full h-[42px] px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500" :disabled="trxType !== 'expense'">
                <option value="">Pilih Kategori Pengeluaran...</option>
                @foreach($expenseCategories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $recurring->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div x-show="trxType === 'income'" style="display: none;">
            <label class="block text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1.5">
                Kategori Pemasukan <span class="text-emerald-500">*</span>
            </label>
            <select name="category_id" class="w-full h-[42px] px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" :disabled="trxType !== 'income'">
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
            <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1.5" x-text="trxType === 'transfer' ? 'Dari Akun Asal *' : (trxType === 'expense' ? 'Dari Dompet / Bank *' : 'Masuk Ke Dompet / Bank *')">
                Akun / Dompet
            </label>
            <select name="account_id" class="w-full h-[42px] px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" required>
                @foreach($cashAccounts as $acc)
                    <option value="{{ $acc->id }}" {{ (old('account_id', $recurring->account_id ?? '') == $acc->id) ? 'selected' : '' }}>
                        {{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div x-show="trxType === 'transfer'" style="display: none;">
            <label class="block text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1.5">
                Ke Akun Tujuan <span class="text-emerald-500">*</span>
            </label>
            <select name="to_account_id" class="w-full h-[42px] px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" :disabled="trxType !== 'transfer'">
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
        <x-input-floating type="text" name="amount" label="Nominal (Rp)" value="{{ old('amount', $recurring->amount ?? '') }}" :isCurrency="true" required="true" />
        @error('amount')
            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Frekuensi & Tanggal Eksekusi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 border border-zinc-200/80 dark:border-zinc-800 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 bg-zinc-50/50 dark:bg-zinc-800/30">
        <div>
            <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                Frekuensi Berulang <span class="text-rose-500">*</span>
            </label>
            <select name="frequency" x-model="freq" class="w-full h-[42px] px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer">
                <option value="monthly">Bulanan (Setiap Bulan)</option>
                <option value="daily">Harian (Setiap Hari)</option>
                <option value="weekly">Mingguan</option>
                <option value="yearly">Tahunan</option>
            </select>
        </div>

        <div x-show="freq === 'monthly'">
            <label class="block text-xs uppercase tracking-wider font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                Tanggal Eksekusi (Tiap Tgl) <span class="text-rose-500">*</span>
            </label>
            <select name="day_of_month" class="w-full h-[42px] px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer">
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
            <x-input-floating type="date" name="start_date" label="Mulai Berlaku" value="{{ old('start_date', isset($recurring->start_date) && $recurring->start_date ? $recurring->start_date->format('Y-m-d') : date('Y-m-d')) }}" required="true" />
        </div>
        <div>
            <x-input-floating type="date" name="end_date" label="Berakhir Pada (Opsional)" value="{{ old('end_date', isset($recurring->end_date) && $recurring->end_date ? $recurring->end_date->format('Y-m-d') : '') }}" />
        </div>
    </div>

    <!-- Catatan -->
    <div>
        <x-input-floating type="text" name="note" label="Catatan Tambahan (Opsional)" value="{{ old('note', $recurring->note ?? '') }}" />
    </div>

    <!-- Is Active -->
    <div>
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
