@extends('admin.layouts.app')

@section('title', 'Pencatatan Keuangan')

@section('content')
<div class="space-y-3.5 sm:space-y-5 pb-6 sm:pb-0" x-data="receiptPreviewManager()">
    <!-- Header (Compact) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5">
        <div>
            <h1 class="text-lg sm:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Pencatatan Keuangan</h1>
            <p class="text-[11px] sm:text-xs text-zinc-500 dark:text-zinc-400">Input cepat transaksi pengeluaran, pemasukan, lampiran bukti, dan transfer/pindah kas antar akun</p>
        </div>
        <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 sm:pb-0 scrollbar-none">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 bg-white/80 dark:bg-zinc-900/80 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition shadow-xs whitespace-nowrap flex-shrink-0">
                <svg class="w-3.5 h-3.5 mr-1 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
            @if(auth()->user() && auth()->user()->hasPermission('view-cash_accounts'))
            <a href="{{ route('admin.cash_accounts.index') }}"
                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 bg-white/80 dark:bg-zinc-900/80 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition shadow-xs whitespace-nowrap flex-shrink-0">
                <svg class="w-3.5 h-3.5 mr-1 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Dompet
            </a>
            @endif
            <a href="{{ route('admin.transaction_categories.index') }}"
                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 bg-white/80 dark:bg-zinc-900/80 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition shadow-xs whitespace-nowrap flex-shrink-0">
                <svg class="w-3.5 h-3.5 mr-1 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Kategori
            </a>
        </div>
    </div>

    <!-- 1. Input Cepat Transaksi & Pindah Saldo (Unified Quick Add Form with Perfect Alignment & Proof Attachment) -->
    @if(auth()->user() && auth()->user()->hasPermission('create-cash_transactions'))
    <div id="quickAddContainer" class="p-3.5 sm:p-5 rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/60 dark:border-zinc-800/60 shadow-xs"
         x-data="{
            trxType: 'expense',
            proofName: '',
            setType(t) {
                this.trxType = t;
            },
            setDate(dateStr) {
                const dateInput = document.getElementById('quick_transaction_date');
                if (dateInput) dateInput.value = dateStr;
            },
            addAmount(addVal) {
                const input = document.getElementById('quick_amount');
                if (!input) return;
                const autoNumeric = typeof AutoNumeric !== 'undefined' ? AutoNumeric.getAutoNumericElement(input) : null;
                const currentVal = autoNumeric ? (autoNumeric.getNumber() || 0) : (parseFloat(input.value.replace(/[^\d.-]/g, '')) || 0);
                const newVal = currentVal + addVal;
                if (autoNumeric) {
                    autoNumeric.set(newVal);
                } else {
                    input.value = newVal;
                }
            },
            resetAmount() {
                const input = document.getElementById('quick_amount');
                if (!input) return;
                const autoNumeric = typeof AutoNumeric !== 'undefined' ? AutoNumeric.getAutoNumericElement(input) : null;
                if (autoNumeric) {
                    autoNumeric.set(0);
                } else {
                    input.value = '';
                }
            },
            onProofSelected(e) {
                const file = e.target.files[0];
                this.proofName = file ? file.name : '';
            },
            clearProof() {
                this.proofName = '';
                const input = document.getElementById('quick_proof_file');
                if (input) input.value = '';
            }
         }">
        
        <!-- Quick Form Header with Type Switcher Tabs -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pb-3 border-b border-zinc-100 dark:border-zinc-800">
            <div class="flex items-center gap-2 sm:gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold transition-all duration-300 shadow-xs flex-shrink-0"
                     :class="trxType === 'expense' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50' : (trxType === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50')">
                    <template x-if="trxType === 'expense'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </template>
                    <template x-if="trxType === 'income'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    </template>
                    <template x-if="trxType === 'transfer'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </template>
                </div>
                <div>
                    <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white" x-text="trxType === 'expense' ? 'Input Pengeluaran' : (trxType === 'income' ? 'Input Pemasukan' : 'Transfer / Pindah Kas Antar Akun')"></h2>
                    <p class="text-[10px] sm:text-[11px] text-zinc-500">Pencatatan kas otomatis update saldo dompet seketika</p>
                </div>
            </div>

            <!-- Mode Selector Tabs -->
            <div class="grid grid-cols-3 gap-1 p-1 bg-zinc-100 dark:bg-zinc-800/80 rounded-xl text-xs font-semibold w-full sm:w-auto">
                <button type="button" 
                        @click="setType('expense')"
                        :class="trxType === 'expense' ? 'bg-rose-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                        class="px-2 py-1.5 rounded-lg transition-all flex items-center justify-center gap-1 text-[11px] sm:text-xs font-bold whitespace-nowrap cursor-pointer">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    <span>Pengeluaran</span>
                </button>
                <button type="button" 
                        @click="setType('income')"
                        :class="trxType === 'income' ? 'bg-emerald-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                        class="px-2 py-1.5 rounded-lg transition-all flex items-center justify-center gap-1 text-[11px] sm:text-xs font-bold whitespace-nowrap cursor-pointer">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    <span>Pemasukan</span>
                </button>
                <button type="button" 
                        @click="setType('transfer')"
                        :class="trxType === 'transfer' ? 'bg-indigo-600 text-white shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                        class="px-2 py-1.5 rounded-lg transition-all flex items-center justify-center gap-1 text-[11px] sm:text-xs font-bold whitespace-nowrap cursor-pointer">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span class="inline sm:hidden">Transfer</span>
                    <span class="hidden sm:inline">Transfer / Pindah</span>
                </button>
            </div>
        </div>

        <form id="quickAddForm" action="{{ route('admin.cash_transactions.store') }}" method="POST" enctype="multipart/form-data" x-data="ajaxForm" @submit.prevent="submit" class="mt-3.5 space-y-3">
            @csrf
            <input type="hidden" name="type" id="quick_type" :value="trxType">

            <!-- Unified 5-Column Grid with Consistent Header Baseline -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2.5 sm:gap-3 items-start">
                
                <!-- 1. Kategori (Expense / Income) ATAU Dari Akun (Transfer) -->
                <div class="min-w-0">
                    <!-- Mode Pengeluaran: Kategori Pengeluaran -->
                    <div x-show="trxType === 'expense'">
                        <div class="flex items-center justify-between min-h-[22px] mb-1.5">
                            <label for="expense_category_id" class="text-[10px] sm:text-[11px] uppercase tracking-wider font-bold text-rose-600 dark:text-rose-400">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                        </div>
                        <select name="category_id" id="expense_category_id" class="w-full max-w-full min-w-0 h-[40px] px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-colors" :disabled="trxType !== 'expense'" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach($expenseCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mode Pemasukan: Kategori Pemasukan -->
                    <div x-show="trxType === 'income'" style="display: none;">
                        <div class="flex items-center justify-between min-h-[22px] mb-1.5">
                            <label for="income_category_id" class="text-[10px] sm:text-[11px] uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400">
                                Kategori <span class="text-emerald-500">*</span>
                            </label>
                        </div>
                        <select name="category_id" id="income_category_id" class="w-full max-w-full min-w-0 h-[40px] px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" :disabled="trxType !== 'income'">
                            <option value="">Pilih Kategori...</option>
                            @foreach($incomeCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mode Transfer: 1. Dari Akun / Sumber Dana -->
                    <div x-show="trxType === 'transfer'" style="display: none;">
                        <div class="flex items-center justify-between min-h-[22px] mb-1.5">
                            <label for="transfer_account_id" class="text-[10px] sm:text-[11px] uppercase tracking-wider font-bold text-rose-600 dark:text-rose-400">
                                1. Dari Akun Asal <span class="text-rose-500">*</span>
                            </label>
                        </div>
                        <select name="account_id" id="transfer_account_id" class="w-full max-w-full min-w-0 h-[40px] px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-colors" :disabled="trxType !== 'transfer'">
                            @foreach($cashAccounts as $acc)
                                <option value="{{ $acc->id }}" {{ $acc->type === 'bank' ? 'selected' : '' }}>{{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 2. Akun Sumber/Tujuan (Non-Transfer) ATAU Ke Dompet Tujuan (Transfer) -->
                <div class="min-w-0">
                    <!-- Non-Transfer Mode -->
                    <div x-show="trxType !== 'transfer'">
                        <div class="flex items-center justify-between min-h-[22px] mb-1.5">
                            <label for="quick_account_id" class="text-[10px] sm:text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400" x-text="trxType === 'expense' ? 'Dari Dompet / Bank' : 'Masuk Ke Dompet'">
                                Akun / Dompet <span class="text-rose-500">*</span>
                            </label>
                        </div>
                        <select name="account_id" id="quick_account_id" class="w-full max-w-full min-w-0 h-[40px] px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors" :disabled="trxType === 'transfer'">
                            @foreach($cashAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Transfer Mode: 2. Ke Dompet / Akun Tujuan -->
                    <div x-show="trxType === 'transfer'" style="display: none;">
                        <div class="flex items-center justify-between min-h-[22px] mb-1.5">
                            <label for="transfer_to_account_id" class="text-[10px] sm:text-[11px] uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400">
                                2. Ke Akun Tujuan <span class="text-emerald-500">*</span>
                            </label>
                        </div>
                        <select name="to_account_id" id="transfer_to_account_id" class="w-full max-w-full min-w-0 h-[40px] px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" :disabled="trxType !== 'transfer'">
                            @foreach($cashAccounts as $acc)
                                <option value="{{ $acc->id }}" {{ $acc->type === 'cash' ? 'selected' : '' }}>{{ $acc->name }} ({{ $acc->type_name ?? ucfirst($acc->type) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 3. Nominal (Single Unified Input) -->
                <div class="min-w-0">
                    <div class="flex items-center justify-between min-h-[22px] mb-1.5">
                        <label for="quick_amount" class="text-[10px] sm:text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">
                            Nominal (Rp) <span class="text-rose-500">*</span>
                        </label>
                    </div>
                    <input type="text" 
                           name="amount" 
                           id="quick_amount" 
                           placeholder="Rp 0" 
                           data-currency 
                           required 
                           class="w-full max-w-full min-w-0 box-border h-[40px] px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs sm:text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors" />
                </div>

                <!-- 4. Tanggal (Single Unified Input) -->
                <div class="min-w-0">
                    <div class="flex items-center justify-between min-h-[22px] mb-1.5 flex-wrap gap-1">
                        <label for="quick_transaction_date" class="text-[10px] sm:text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="setDate('{{ date('Y-m-d') }}')" class="text-[10px] text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Hari Ini</button>
                            <span class="text-[10px] text-zinc-300 dark:text-zinc-700">•</span>
                            <button type="button" @click="setDate('{{ date('Y-m-d', strtotime('-1 day')) }}')" class="text-[10px] text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Kemarin</button>
                        </div>
                    </div>
                    <input type="date" 
                           name="transaction_date" 
                           id="quick_transaction_date" 
                           value="{{ date('Y-m-d') }}" 
                           required 
                           class="w-full max-w-full min-w-0 box-border h-[40px] px-2.5 sm:px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors cursor-pointer" />
                </div>

                <!-- 5. Catatan & Bukti File Picker -->
                <div class="min-w-0">
                    <div class="flex items-center justify-between min-h-[22px] mb-1.5">
                        <label for="quick_note" class="text-[10px] sm:text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">
                            Catatan / Bukti
                        </label>
                        <label for="quick_proof_file" class="text-[10px] text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer font-medium flex items-center gap-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span x-text="proofName ? 'Ganti Bukti' : '+ Bukti'"></span>
                        </label>
                        <input type="file" name="proof" id="quick_proof_file" accept="image/*,application/pdf" class="hidden" @change="onProofSelected($event)">
                    </div>
                    <div class="relative">
                        <input type="text" 
                               name="note" 
                               id="quick_note" 
                               placeholder="Contoh: Belanja dapur" 
                               class="w-full max-w-full min-w-0 box-border h-[40px] px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors" />
                        
                        <!-- Attached File Badge -->
                        <div x-show="proofName" class="mt-1 flex items-center justify-between px-2 py-1 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-900/60 rounded-lg text-[10px] text-indigo-700 dark:text-indigo-300" style="display: none;">
                            <span class="truncate max-w-[140px]" x-text="'📎 ' + proofName"></span>
                            <button type="button" @click="clearProof()" class="text-rose-500 hover:text-rose-700 font-bold ml-1">✕</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Amount Increment Shortcuts & Submit -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pt-0.5">
                <div class="flex flex-wrap items-center gap-1 text-xs">
                    <span class="text-zinc-400 mr-0.5 text-[10px] hidden sm:inline">Tambah:</span>
                    <button type="button" @click="addAmount(50000)" class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition font-medium text-[10px] sm:text-[11px]">+50rb</button>
                    <button type="button" @click="addAmount(100000)" class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition font-medium text-[10px] sm:text-[11px]">+100rb</button>
                    <button type="button" @click="addAmount(200000)" class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition font-medium text-[10px] sm:text-[11px]">+200rb</button>
                    <button type="button" @click="addAmount(500000)" class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition font-medium text-[10px] sm:text-[11px]">+500rb</button>
                    <button type="button" @click="addAmount(1000000)" class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition font-medium text-[10px] sm:text-[11px]">+1 Juta</button>
                    <button type="button" @click="resetAmount()" class="px-2 py-0.5 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition font-medium text-[10px] sm:text-[11px]">Reset</button>
                </div>

                <div class="w-full sm:w-auto">
                    <button type="submit" 
                            :disabled="loading"
                            class="w-full sm:w-auto px-4 py-2 text-white text-xs sm:text-sm font-semibold rounded-xl transition-all shadow-xs inline-flex items-center justify-center gap-1.5"
                            :class="trxType === 'expense' ? 'bg-rose-600 hover:bg-rose-700 shadow-rose-500/20' : (trxType === 'income' ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-500/20')">
                        <svg x-show="!loading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <svg x-show="loading" class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Menyimpan...' : (trxType === 'expense' ? 'Simpan Pengeluaran' : (trxType === 'income' ? 'Simpan Pemasukan' : 'Proses Pindah Kas'))"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- 2. Filter Bar & Ringkasan Periode -->
    <div class="space-y-3">
        @include('admin.partials.financial-filter-bar', [
            'dateRange' => $dateRange,
            'route' => route('admin.cash_transactions.index'),
            'exportRoute' => route('admin.cash_transactions.export'),
            'showExport' => true,
            'showDimensions' => true,
            'allCategories' => $allCategories,
            'cashAccounts' => $cashAccounts,
            'selectedType' => $type,
            'selectedCategory' => $categoryId,
            'selectedAccount' => $accountId,
        ])

        <!-- 4 Stat Summary Cards for Active Filter -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
            <!-- Pemasukan -->
            <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/60 dark:border-zinc-800/60 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Pemasukan</span>
                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    </div>
                </div>
                <div class="mt-1.5 sm:mt-2">
                    <h3 class="text-xs sm:text-lg font-extrabold tracking-tight text-emerald-600 dark:text-emerald-400">
                        +Rp {{ number_format($filteredSummary['total_income'], 0, ',', '.') }}
                    </h3>
                </div>
                <div class="mt-2 pt-1.5 border-t border-zinc-100 dark:border-zinc-800 text-[10px] text-zinc-400 truncate">
                    Periode: {{ $dateRange['label'] }}
                </div>
            </div>

            <!-- Pengeluaran -->
            <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/60 dark:border-zinc-800/60 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Pengeluaran</span>
                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>
                </div>
                <div class="mt-1.5 sm:mt-2">
                    <h3 class="text-xs sm:text-lg font-extrabold tracking-tight text-rose-600 dark:text-rose-400">
                        -Rp {{ number_format($filteredSummary['total_expense'], 0, ',', '.') }}
                    </h3>
                </div>
                <div class="mt-2 pt-1.5 border-t border-zinc-100 dark:border-zinc-800 text-[10px] text-zinc-400 truncate">
                    Periode: {{ $dateRange['label'] }}
                </div>
            </div>

            <!-- Net Tabungan (Surplus / Defisit) -->
            <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/60 dark:border-zinc-800/60 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Net Tabungan</span>
                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
                <div class="mt-1.5 sm:mt-2">
                    <h3 class="text-xs sm:text-lg font-extrabold tracking-tight {{ $filteredSummary['net_savings'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $filteredSummary['net_savings'] >= 0 ? '+' : '' }}Rp {{ number_format($filteredSummary['net_savings'], 0, ',', '.') }}
                    </h3>
                </div>
                <div class="mt-2 pt-1.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-[10px]">
                    <span class="px-1.5 py-0.5 rounded font-semibold {{ $filteredSummary['net_savings'] >= 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' }}">
                        {{ $filteredSummary['net_savings'] >= 0 ? 'Surplus' : 'Defisit' }}
                    </span>
                    <span class="text-zinc-400">Bersih</span>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/60 dark:border-zinc-800/60 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Total Transaksi</span>
                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <div class="mt-1.5 sm:mt-2">
                    <h3 class="text-xs sm:text-lg font-extrabold tracking-tight text-zinc-900 dark:text-white">
                        {{ $filteredSummary['transaction_count'] }} <span class="text-xs font-normal text-zinc-500">Mutasi</span>
                    </h3>
                </div>
                <div class="mt-2 pt-1.5 border-t border-zinc-100 dark:border-zinc-800 text-[10px] text-zinc-400 flex items-center justify-between">
                    <span>Export Tersedia</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">Excel .xlsx</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Daftar Riwayat Transaksi -->
    <div class="space-y-2.5 sm:space-y-3.5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">Riwayat Transaksi & Mutasi Kas ({{ $dateRange['label'] }})</h2>
                <p class="text-[10px] sm:text-[11px] text-zinc-500">Mutasi kas, transfer/pindah saldo, lampiran struk, dan transaksi harian</p>
            </div>
        </div>

        <!-- Desktop View (PowerGrid Table) -->
        <div class="hidden md:block bg-white dark:bg-zinc-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-zinc-100/50 dark:border-zinc-800 overflow-hidden p-2">
            <livewire:tables.cash-transaction-table />
        </div>

        <!-- Mobile View (Card List Grouped by Date) -->
        <div class="block md:hidden space-y-2.5">
            @forelse($mobileTransactions as $dateKey => $items)
                <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-zinc-200/60 dark:border-zinc-800/60 rounded-xl sm:rounded-2xl shadow-xs overflow-hidden">
                    <!-- Date Header -->
                    <div class="px-3 py-1.5 bg-zinc-50/80 dark:bg-zinc-800/60 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300">
                            {{ $dateKey !== 'No Date' ? \Carbon\Carbon::parse($dateKey)->translatedFormat('l, d M Y') : 'Tanpa Tanggal' }}
                        </span>
                        @php
                            $dailyNet = $items->reduce(function ($carry, $t) {
                                if ($t->type === 'income') return $carry + $t->amount;
                                if ($t->type === 'expense') return $carry - $t->amount;
                                return $carry;
                            }, 0);
                        @endphp
                        <span class="text-[11px] font-bold {{ $dailyNet >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $dailyNet >= 0 ? '+' : '' }}Rp {{ number_format($dailyNet, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- List of items for this date -->
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($items as $tx)
                            <div class="p-2.5 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-7 h-7 rounded-lg flex-shrink-0 flex items-center justify-center {{ $tx->type === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400' : ($tx->type === 'expense' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400') }}">
                                        @if ($tx->type === 'income')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                        @elseif ($tx->type === 'expense')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-zinc-900 dark:text-white truncate">
                                            @if($tx->type === 'transfer')
                                                Pindah: {{ $tx->account->name ?? 'Bank' }} ➔ {{ $tx->toAccount->name ?? 'Kas' }}
                                            @else
                                                {{ $tx->category->name ?? 'Kategori Lain' }}
                                            @endif
                                        </p>
                                        <div class="flex items-center gap-1 text-[10px] text-zinc-400 truncate">
                                            @if($tx->type !== 'transfer' && $tx->account)
                                                <span>{{ $tx->account->name }}</span>
                                                <span>•</span>
                                            @endif
                                            <span>{{ $tx->user->name ?? 'Admin' }}</span>
                                            @if ($tx->note)
                                                <span>•</span>
                                                <span class="truncate max-w-[90px]">{{ $tx->note }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs font-bold {{ $tx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($tx->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-indigo-600 dark:text-indigo-400') }}">
                                        {{ $tx->type === 'income' ? '+' : ($tx->type === 'expense' ? '-' : '') }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                        @if($tx->proof)
                                            <button type="button" 
                                                    @click="openReceipt('{{ $tx->proof_url }}', 'Bukti Transaksi #TRX-{{ $tx->id }}')" 
                                                    class="text-[10px] font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-0.5">
                                                <span>📎 Struk</span>
                                            </button>
                                            <span class="text-zinc-300 dark:text-zinc-700 text-[10px]">•</span>
                                        @endif
                                        @if (auth()->user() && auth()->user()->hasPermission('edit-cash_transactions'))
                                            <a href="{{ route('admin.cash_transactions.edit', $tx->id) }}" class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                        @endif
                                        @if (auth()->user() && auth()->user()->hasPermission('delete-cash_transactions'))
                                            <span class="text-zinc-300 dark:text-zinc-700 text-[10px]">•</span>
                                            <button type="button" 
                                                    onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { action: '{{ route('admin.cash_transactions.destroy', $tx->id) }}' } }))"
                                                    class="text-[10px] font-semibold text-rose-600 dark:text-rose-400 hover:underline">Hapus</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-5 text-center bg-white/80 dark:bg-zinc-900/80 rounded-xl border border-zinc-200/60 dark:border-zinc-800 text-zinc-400 text-xs">
                    Belum ada transaksi tercatat.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Universal Receipt Preview Modal -->
    <template x-teleport="body">
        <div x-show="showReceiptModal" style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="showReceiptModal = false"
             class="fixed inset-0 z-[100] overflow-y-auto bg-black/80 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div @click.away="showReceiptModal = false"
                 x-show="showReceiptModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-zinc-900 rounded-2xl max-w-3xl w-full p-2 overflow-hidden shadow-2xl border border-zinc-800 my-auto">
                <div class="flex items-center justify-between p-3 border-b border-zinc-800 text-white">
                    <span class="text-xs font-semibold" x-text="receiptTitle || 'Bukti Struk Transaksi'"></span>
                    <div class="flex items-center gap-2">
                        <a :href="receiptUrl" target="_blank" download class="px-2.5 py-1 text-xs bg-zinc-800 hover:bg-zinc-700 text-white rounded-lg transition inline-flex items-center gap-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh
                        </a>
                        <button type="button" @click="showReceiptModal = false" class="p-1 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="p-2 max-h-[80vh] overflow-auto flex items-center justify-center">
                    <template x-if="!isPdf">
                        <img :src="receiptUrl" alt="Bukti Transaksi" class="max-w-full max-h-[75vh] rounded-lg object-contain">
                    </template>
                    <template x-if="isPdf">
                        <div class="p-8 text-center text-white">
                            <svg class="w-16 h-16 text-rose-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <p class="text-sm font-bold mb-3">Dokumen PDF Terlampir</p>
                            <a :href="receiptUrl" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition cursor-pointer">
                                Buka Dokumen PDF
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>

<!-- Delete Confirmation Modal -->
<x-confirm-delete-modal title="Hapus Transaksi Kas"
    message="Apakah Anda yakin ingin menghapus transaksi ini? Data yang dihapus dapat dipulihkan oleh admin." />
@endsection

@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $selectFields = [];
        $currencyFields = ['amount'];
    @endphp
    @include('admin.partials.form-scripts')

    <script>
        function receiptPreviewManager() {
            return {
                showReceiptModal: false,
                receiptUrl: '',
                receiptTitle: '',
                isPdf: false,
                init() {
                    window.addEventListener('preview-receipt', (e) => {
                        this.openReceipt(e.detail.url, e.detail.title);
                    });
                },
                openReceipt(url, title) {
                    this.receiptUrl = url;
                    this.receiptTitle = title;
                    this.isPdf = url.toLowerCase().endsWith('.pdf');
                    this.showReceiptModal = true;
                }
            }
        }
    </script>
@endpush
