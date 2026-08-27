@php
    $defaultOptions = [
        'cash' => 'Tunai',
        'bank' => 'Bank',
        'ewallet' => 'E-Wallet',
        'investment' => 'Investasi',
        'loan' => 'Pinjaman / Paylater',
        'other' => 'Lainnya',
    ];
    $typeOptions = !empty($accountTypes) ? $accountTypes : $defaultOptions;
    $selectedTypeInit = old('type', $cashAccount->type ?? 'cash');
    $selectedColorInit = old('color', $cashAccount->color ?? 'emerald');
    $initBalanceVal = old('initial_balance', $cashAccount->initial_balance ?? 0);
@endphp

<div x-data="cashAccountForm()" class="space-y-6">

    <!-- 1. Live Preview Card -->
    <div class="p-4 sm:p-5 rounded-2xl border border-zinc-200/80 dark:border-zinc-800/80 bg-zinc-50/50 dark:bg-zinc-900/50">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Pratinjau Kartu Dompet</span>
            <span class="text-[11px] text-zinc-400 font-medium" x-text="isActive ? 'Status: Aktif' : 'Status: Non-Aktif'"></span>
        </div>

        <div class="relative overflow-hidden rounded-2xl p-4 sm:p-5 transition-all duration-300 shadow-sm border"
             :class="getColorCardClasses(selectedColor)">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wide uppercase bg-black/10 dark:bg-white/10"
                          x-text="getTypeLabel(selectedType)"></span>
                    <h3 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white mt-1.5 truncate"
                        x-text="accountName || 'Nama Dompet / Rekening'"></h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-300 font-mono mt-0.5"
                       x-text="accountNumber ? ('No: ' + accountNumber) : 'Tanpa nomor rekening'"></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/40 dark:bg-black/20 backdrop-blur-md flex items-center justify-center flex-shrink-0 shadow-xs">
                    <template x-if="selectedType === 'bank'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </template>
                    <template x-if="selectedType === 'ewallet'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </template>
                    <template x-if="selectedType === 'cash'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </template>
                    <template x-if="selectedType !== 'bank' && selectedType !== 'ewallet' && selectedType !== 'cash'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    </template>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-black/10 dark:border-white/10 flex items-center justify-between">
                <span class="text-xs text-zinc-600 dark:text-zinc-400 font-medium">Saldo Awal</span>
                <span class="text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white"
                      x-text="formatBalance(balanceDisplay)"></span>
            </div>
        </div>
    </div>

    <!-- 2. Form Section: Informasi Utama -->
    <div class="space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-2 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
            Informasi Dompet & Rekening
        </h4>

        <!-- Nama Dompet -->
        <div>
            <label for="name_input" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                Nama Akun / Dompet <span class="text-rose-500">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name_input"
                   x-model="accountName"
                   placeholder="Contoh: BCA Tabungan Utama, Dompet Tunai Harian, GoPay"
                   required
                   class="w-full h-[44px] px-3.5 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" />
        </div>

        <!-- Tipe Akun Selector (Visual Pill Badges + Dropdown) -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Tipe Akun <span class="text-rose-500">*</span>
                </label>
                <button type="button" @click="openQuickTypeModal = true" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 inline-flex items-center gap-1">
                    + Tipe Baru
                </button>
            </div>

            <!-- Dynamic Quick Select Options -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <template x-for="opt in typeList" :key="opt.code">
                    <button type="button"
                            @click="selectedType = opt.code"
                            class="p-2.5 rounded-xl border text-left transition flex items-center gap-2 cursor-pointer"
                            :class="selectedType === opt.code ? 'border-indigo-600 bg-indigo-50/70 text-indigo-900 dark:bg-indigo-950/50 dark:text-indigo-200 dark:border-indigo-500 shadow-xs' : 'border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/60 text-zinc-700 dark:text-zinc-300'">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center text-xs font-bold"
                             :class="selectedType === opt.code ? 'bg-indigo-600 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300'">
                            <template x-if="opt.code === 'cash'"><span>💵</span></template>
                            <template x-if="opt.code === 'bank'"><span>🏦</span></template>
                            <template x-if="opt.code === 'ewallet'"><span>📱</span></template>
                            <template x-if="opt.code === 'investment'"><span>📈</span></template>
                            <template x-if="opt.code === 'loan'"><span>💳</span></template>
                            <template x-if="opt.code !== 'cash' && opt.code !== 'bank' && opt.code !== 'ewallet' && opt.code !== 'investment' && opt.code !== 'loan'"><span>🏷️</span></template>
                        </div>
                        <span class="text-xs font-semibold truncate" x-text="opt.name"></span>
                    </button>
                </template>
            </div>
            <input type="hidden" name="type" :value="selectedType">
        </div>

        <!-- Nomor Rekening / Keterangan -->
        <div>
            <label for="account_number_input" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                Nomor Rekening / No. HP E-Wallet <span class="text-zinc-400 font-normal">(Opsional)</span>
            </label>
            <input type="text"
                   name="account_number"
                   id="account_number_input"
                   x-model="accountNumber"
                   placeholder="Contoh: 123-456-7890 atau 08123456789"
                   class="w-full h-[44px] px-3.5 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" />
        </div>
    </div>

    <!-- 3. Form Section: Saldo & Tampilan -->
    <div class="space-y-4 pt-2">
        <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-2 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
            Saldo Awal & Warna Tema
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Saldo Awal -->
            <div>
                <label for="initial_balance_input" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Saldo Awal (Rp) <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="initial_balance"
                       id="initial_balance_input"
                       data-currency
                       value="{{ $initBalanceVal }}"
                       @input="onBalanceChange($event)"
                       required
                       class="w-full h-[44px] px-3.5 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition" />
                <p class="text-[11px] text-zinc-400 mt-1">Saldo pembukaan awal saat pertama kali mendaftarkan akun.</p>
            </div>

            <!-- Warna Tema Visual Swatches -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Warna Tema Kartu
                </label>
                <div class="flex flex-wrap items-center gap-2 pt-0.5">
                    <template x-for="c in colorOptions" :key="c.name">
                        <button type="button"
                                @click="selectedColor = c.name"
                                :title="c.label"
                                class="w-9 h-9 rounded-xl transition-transform flex items-center justify-center shadow-xs border-2"
                                :class="[c.bgClass, selectedColor === c.name ? 'scale-110 border-zinc-900 dark:border-white ring-2 ring-indigo-500/30' : 'border-transparent hover:scale-105']">
                            <svg x-show="selectedColor === c.name" class="w-4 h-4 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </template>
                </div>
                <input type="hidden" name="color" :value="selectedColor">
            </div>
        </div>

        <!-- Status Aktif Toggle -->
        <div class="pt-2">
            <x-toggle name="is_active" label="Status Aktif" :checked="$cashAccount->is_active ?? true" />
            <p class="text-[11px] text-zinc-400 mt-0.5">Akun yang aktif akan muncul di pilihan transaksi kas harian.</p>
        </div>
    </div>

    <!-- Quick Add Type Modal (Inline within form) -->
    <div x-show="openQuickTypeModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openQuickTypeModal" @click="openQuickTypeModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="openQuickTypeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-2xl px-5 pt-5 pb-6 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-zinc-200 dark:border-zinc-800">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </span>
                        Tambah Tipe Akun Baru
                    </h3>
                    <button type="button" @click="openQuickTypeModal = false" class="text-zinc-400 hover:text-zinc-500 dark:hover:text-zinc-300 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Nama Tipe Akun <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="newTypeName" placeholder="Contoh: Koperasi, Tabungan Khusus, Crypto" class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Deskripsi / Keterangan (Opsional)</label>
                        <input type="text" x-model="newTypeDesc" placeholder="Keterangan singkat tipe akun..." class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-300 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none" />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2.5">
                    <button type="button" @click="openQuickTypeModal = false" class="px-4 py-2 text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" @click="saveQuickType()" :disabled="savingType || !newTypeName.trim()" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                        <svg x-show="savingType" class="animate-spin -ml-1 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span x-text="savingType ? 'Menyimpan...' : 'Simpan Tipe'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $selectFields = [];
        $currencyFields = ['initial_balance'];
    @endphp
    @include('admin.partials.form-scripts')

    <script>
        function cashAccountForm() {
            return {
                accountName: '{{ addslashes($cashAccount->name ?? '') }}',
                accountNumber: '{{ addslashes($cashAccount->account_number ?? '') }}',
                selectedType: '{{ $selectedTypeInit }}',
                selectedColor: '{{ $selectedColorInit }}',
                balanceDisplay: '{{ $initBalanceVal }}',
                isActive: {{ isset($cashAccount->is_active) ? ($cashAccount->is_active ? 'true' : 'false') : 'true' }},
                openQuickTypeModal: false,
                savingType: false,
                newTypeName: '',
                newTypeDesc: '',
                colorOptions: [
                    { name: 'emerald', label: 'Hijau Emerald', bgClass: 'bg-emerald-500' },
                    { name: 'blue', label: 'Biru Cerah', bgClass: 'bg-blue-500' },
                    { name: 'indigo', label: 'Indigo / Navy', bgClass: 'bg-indigo-600' },
                    { name: 'purple', label: 'Ungu Elegan', bgClass: 'bg-purple-600' },
                    { name: 'rose', label: 'Merah Rose', bgClass: 'bg-rose-500' },
                    { name: 'amber', label: 'Kuning Emas', bgClass: 'bg-amber-500' },
                    { name: 'cyan', label: 'Cyan Laut', bgClass: 'bg-cyan-500' },
                    { name: 'zinc', label: 'Abu-Abu Metal', bgClass: 'bg-zinc-600' }
                ],
                typeList: [
                    @foreach($typeOptions as $code => $name)
                        { code: '{{ $code }}', name: '{{ $name }}' },
                    @endforeach
                ],
                onBalanceChange(e) {
                    this.balanceDisplay = e.target.value;
                },
                getTypeLabel(code) {
                    const match = this.typeList.find(t => t.code === code);
                    return match ? match.name : code;
                },
                getColorCardClasses(color) {
                    switch(color) {
                        case 'blue':
                            return 'bg-blue-50/80 dark:bg-blue-950/40 border-blue-200 dark:border-blue-800 text-blue-950 dark:text-blue-100';
                        case 'indigo':
                            return 'bg-indigo-50/80 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800 text-indigo-950 dark:text-indigo-100';
                        case 'purple':
                            return 'bg-purple-50/80 dark:bg-purple-950/40 border-purple-200 dark:border-purple-800 text-purple-950 dark:text-purple-100';
                        case 'rose':
                            return 'bg-rose-50/80 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-950 dark:text-rose-100';
                        case 'amber':
                            return 'bg-amber-50/80 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-950 dark:text-amber-100';
                        case 'cyan':
                            return 'bg-cyan-50/80 dark:bg-cyan-950/40 border-cyan-200 dark:border-cyan-800 text-cyan-950 dark:text-cyan-100';
                        case 'zinc':
                            return 'bg-zinc-100 dark:bg-zinc-800/80 border-zinc-300 dark:border-zinc-700 text-zinc-900 dark:text-zinc-100';
                        case 'emerald':
                        default:
                            return 'bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-950 dark:text-emerald-100';
                    }
                },
                formatBalance(val) {
                    if (!val) return 'Rp 0';
                    const num = typeof val === 'number' ? val : parseFloat(String(val).replace(/[^\d.-]/g, ''));
                    if (isNaN(num)) return 'Rp 0';
                    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
                },
                saveQuickType() {
                    if (!this.newTypeName.trim()) return;
                    this.savingType = true;
                    fetch('{{ route('admin.cash_account_types.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.newTypeName,
                            description: this.newTypeDesc
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.savingType = false;
                        if (data.success && data.data) {
                            this.typeList.push({
                                code: data.data.code,
                                name: data.data.name
                            });
                            this.selectedType = data.data.code;
                            this.openQuickTypeModal = false;
                            this.newTypeName = '';
                            this.newTypeDesc = '';
                            if (window.toast) {
                                window.toast.success('Tipe akun berhasil ditambahkan!');
                            }
                        } else {
                            alert(data.message || 'Gagal menambahkan tipe akun');
                        }
                    })
                    .catch(err => {
                        this.savingType = false;
                        alert('Terjadi kesalahan koneksi');
                    });
                }
            }
        }
    </script>
@endpush
