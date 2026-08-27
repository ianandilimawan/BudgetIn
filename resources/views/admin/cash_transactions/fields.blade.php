<div x-data="{ 
    trxType: '{{ old('type', $cashTransaction->type ?? 'expense') }}',
    proofPreview: '{{ $cashTransaction->proof_url ?? '' }}',
    proofName: '',
    isPdf: {{ isset($cashTransaction->proof) && str_ends_with(strtolower($cashTransaction->proof), '.pdf') ? 'true' : 'false' }},
    setType(t) {
        this.trxType = t;
    },
    handleFileChange(e) {
        const file = e.target.files[0];
        if (!file) return;
        this.proofName = file.name;
        this.isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
        if (!this.isPdf && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.proofPreview = event.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            this.proofPreview = '';
        }
    },
    removeProof() {
        this.proofPreview = '';
        this.proofName = '';
        this.isPdf = false;
        const fileInput = document.getElementById('proof_input');
        if (fileInput) fileInput.value = '';
        const removeInput = document.getElementById('remove_proof_input');
        if (removeInput) removeInput.value = '1';
    }
}" class="space-y-5">

    <!-- Tipe Transaksi Switcher -->
    <div>
        <label class="block text-xs uppercase tracking-wider font-bold text-gray-700 dark:text-gray-300 mb-2">
            Tipe Transaksi <span class="text-rose-500">*</span>
        </label>
        <div class="inline-flex p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-xs font-semibold">
            <button type="button" 
                    @click="setType('expense')"
                    :class="trxType === 'expense' ? 'bg-rose-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="px-4 py-2 rounded-lg transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                <span>Pengeluaran</span>
            </button>
            <button type="button" 
                    @click="setType('income')"
                    :class="trxType === 'income' ? 'bg-emerald-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="px-4 py-2 rounded-lg transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                <span>Pemasukan</span>
            </button>
            <button type="button" 
                    @click="setType('transfer')"
                    :class="trxType === 'transfer' ? 'bg-indigo-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                    class="px-4 py-2 rounded-lg transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span>Tarik Tunai / Transfer</span>
            </button>
        </div>
        <input type="hidden" name="type" :value="trxType">
    </div>

    <!-- Kategori: Hanya tampil jika bukan Transfer -->
    <div x-show="trxType !== 'transfer'">
        <!-- Mode Pengeluaran -->
        <div x-show="trxType === 'expense'">
            <label class="block text-xs uppercase tracking-wider font-bold text-rose-600 dark:text-rose-400 mb-1.5">
                Kategori Pengeluaran <span class="text-rose-500">*</span>
            </label>
            <select name="category_id" class="w-full h-[46px] px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-colors" :disabled="trxType !== 'expense'">
                <option value="">Pilih Kategori Pengeluaran...</option>
                @foreach($expenseCategories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $cashTransaction->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Mode Pemasukan -->
        <div x-show="trxType === 'income'" style="display: none;">
            <label class="block text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1.5">
                Kategori Pemasukan <span class="text-emerald-500">*</span>
            </label>
            <select name="category_id" class="w-full h-[46px] px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" :disabled="trxType !== 'income'">
                <option value="">Pilih Kategori Pemasukan...</option>
                @foreach($incomeCategories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $cashTransaction->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Akun / Dompet Asal -->
    <div>
        <label class="block text-xs uppercase tracking-wider font-bold text-gray-700 dark:text-gray-300 mb-1.5" x-text="trxType === 'transfer' ? 'Dari Akun / Sumber Dana *' : (trxType === 'expense' ? 'Dari Dompet / Bank *' : 'Masuk Ke Dompet / Bank *')">
            Akun / Dompet
        </label>
        <select name="account_id" class="w-full h-[46px] px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors" required>
            @foreach($cashAccounts as $acc)
                <option value="{{ $acc->id }}" {{ (old('account_id', $cashTransaction->account_id ?? '') == $acc->id) ? 'selected' : '' }}>
                    {{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Ke Akun (Hanya tampil untuk Transfer) -->
    <div x-show="trxType === 'transfer'" style="display: none;">
        <label class="block text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1.5">
            Ke Dompet / Akun Tujuan <span class="text-emerald-500">*</span>
        </label>
        <select name="to_account_id" class="w-full h-[46px] px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" :disabled="trxType !== 'transfer'">
            <option value="">Pilih Akun Tujuan...</option>
            @foreach($cashAccounts as $acc)
                <option value="{{ $acc->id }}" {{ (old('to_account_id', $cashTransaction->to_account_id ?? '') == $acc->id) ? 'selected' : '' }}>
                    {{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Nominal -->
    <x-input-floating type="text" name="amount" label="Nominal (Rp)" value="{{ $cashTransaction->amount ?? '' }}" :isCurrency="true" required />

    <!-- Tanggal Transaksi -->
    <x-input-floating type="date" name="transaction_date" label="Tanggal Transaksi" value="{{ isset($cashTransaction->transaction_date) && $cashTransaction->transaction_date ? (is_string($cashTransaction->transaction_date) ? $cashTransaction->transaction_date : $cashTransaction->transaction_date->format('Y-m-d')) : date('Y-m-d') }}" required />

    <!-- Catatan -->
    <x-input-floating type="text" name="note" label="Catatan (Opsional)" value="{{ $cashTransaction->note ?? '' }}" />

    <!-- Bukti / Struk / Nota (Optional) -->
    <div>
        <label class="block text-xs uppercase tracking-wider font-bold text-gray-700 dark:text-gray-300 mb-2">
            Bukti Transaksi / Struk / Nota <span class="text-xs font-normal text-zinc-400 normal-case">(Opsional - JPG, PNG, WEBP, PDF)</span>
        </label>
        
        <input type="hidden" name="remove_proof" id="remove_proof_input" value="0">
        
        <div class="flex items-start gap-4">
            <!-- Upload Box -->
            <label class="flex-1 flex flex-col items-center justify-center p-4 border-2 border-dashed border-zinc-300 dark:border-zinc-700 hover:border-indigo-500 dark:hover:border-indigo-500 rounded-2xl cursor-pointer bg-zinc-50/50 dark:bg-zinc-800/50 transition-colors group">
                <input type="file" name="proof" id="proof_input" accept="image/*,application/pdf" class="hidden" @change="handleFileChange($event)">
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Upload Foto Struk / Dokumen</span>
                    <span class="text-[11px] text-zinc-400 mt-0.5" x-text="proofName || 'Maksimal 10MB (Foto struk belanja, invoice, bukti transfer)'"></span>
                </div>
            </label>

            <!-- Preview Card (if file chosen or existing) -->
            <div x-show="proofPreview || isPdf" class="w-32 h-28 relative rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 overflow-hidden flex-shrink-0 flex items-center justify-center group" style="display: none;">
                <template x-if="proofPreview && !isPdf">
                    <img :src="proofPreview" alt="Bukti Transaksi" class="w-full h-full object-cover">
                </template>
                <template x-if="isPdf">
                    <div class="flex flex-col items-center justify-center text-rose-500 p-2 text-center">
                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-bold">Dokumen PDF</span>
                    </div>
                </template>
                <button type="button" @click="removeProof()" class="absolute top-1.5 right-1.5 p-1 bg-zinc-900/80 hover:bg-rose-600 text-white rounded-lg transition-colors shadow-sm" title="Hapus Bukti">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $selectFields = [];
        $currencyFields = ['amount'];
    @endphp
    @include('admin.partials.form-scripts')
@endpush