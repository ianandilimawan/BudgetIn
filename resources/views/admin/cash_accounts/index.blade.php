@extends('admin.layouts.app')

@section('title', 'Dompet & Akun Kas')

@section('content')
    <div class="space-y-6" x-data="accountTypesManager()">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white">Dompet & Rekening Kas</h2>
                <p class="mt-0.5 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Kelola sumber dana, dompet tunai, rekening bank, dan master tipe akun.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="openModal = true; loadTypes();"
                    class="px-3.5 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 rounded-xl text-xs sm:text-sm font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Master Tipe Akun
                </button>

                @if(auth()->user() && auth()->user()->hasPermission('create-cash_accounts'))
                <a href="{{ route('admin.cash_accounts.create') }}"
                    class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs sm:text-sm font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm shadow-indigo-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Akun
                </a>
                @endif
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-zinc-100/80 dark:border-zinc-800/80 overflow-hidden p-2">
            <livewire:tables.cash-account-table />
        </div>

        <!-- Master Account Types Modal (Standard Fixed Teleport Modal) -->
        <template x-teleport="body">
            <div x-show="openModal" style="display: none;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @keydown.escape.window="openModal = false"
                 class="fixed inset-0 z-[100] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
                 role="dialog" aria-modal="true">

                <div @click.away="openModal = false"
                     x-show="openModal"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="w-full max-w-lg bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden flex flex-col max-h-[90vh] my-auto">

                    <!-- Modal Header -->
                    <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between flex-shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Master Tipe Akun Kas</h3>
                                <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Tambah dan kelola kategori tipe akun sesuai kebutuhan</p>
                            </div>
                        </div>
                        <button type="button" @click="openModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Modal Body (Scrollable) -->
                    <div class="p-5 overflow-y-auto space-y-4 flex-1">
                        <!-- Quick Add / Edit Form -->
                        <div class="p-3.5 bg-zinc-50 dark:bg-zinc-800/40 rounded-xl border border-zinc-200/80 dark:border-zinc-700/80">
                            <div class="text-xs font-semibold text-zinc-800 dark:text-zinc-200 mb-2.5 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                <span x-text="editingId ? 'Edit Tipe Akun' : 'Tambah Tipe Baru'"></span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-3">
                                <div>
                                    <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-400 mb-1">Nama Tipe <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="typeName" placeholder="Contoh: Koperasi / Paylater"
                                           class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-xl text-zinc-900 dark:text-white outline-none focus:ring-1 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-400 mb-1">Kode (Opsional)</label>
                                    <input type="text" x-model="typeCode" placeholder="otomatis_dari_nama"
                                           class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-xl text-zinc-900 dark:text-white outline-none focus:ring-1 focus:ring-indigo-500" />
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" x-show="editingId" @click="resetForm()" class="px-3 py-1.5 text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition-colors cursor-pointer">
                                    Batal Edit
                                </button>
                                <button type="button" @click="saveType()" :disabled="saving || !typeName.trim()"
                                        class="px-4 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 rounded-xl shadow-xs transition-all inline-flex items-center gap-1.5 cursor-pointer">
                                    <svg x-show="saving" class="animate-spin -ml-1 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span x-text="saving ? 'Menyimpan...' : (editingId ? 'Update Tipe' : 'Tambah Tipe')"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Types List -->
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-0.5">
                            <template x-for="item in typeList" :key="item.id">
                                <div class="flex items-center justify-between p-2.5 bg-white dark:bg-zinc-800/80 border border-zinc-200/80 dark:border-zinc-700/80 rounded-xl hover:border-zinc-300 dark:hover:border-zinc-600 transition-colors shadow-2xs">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-zinc-600 dark:text-zinc-300 font-bold text-xs flex-shrink-0">
                                            <span x-text="item.name.substring(0, 1).toUpperCase()"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span class="text-xs font-bold text-zinc-900 dark:text-white truncate" x-text="item.name"></span>
                                                <span x-show="item.is_system || item.user_id === null" class="px-1.5 py-0.2 text-[9px] font-bold rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                                    Bawaan
                                                </span>
                                                <span x-show="!item.is_system && item.user_id !== null" class="px-1.5 py-0.2 text-[9px] font-bold rounded bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/60">
                                                    Kustom
                                                </span>
                                            </div>
                                            <div class="text-[10px] text-zinc-500 dark:text-zinc-400 flex items-center gap-2 mt-0.5">
                                                <span>Kode: <code class="bg-zinc-100 dark:bg-zinc-900 px-1 py-0.5 rounded text-[9px]" x-text="item.code"></code></span>
                                                <span x-show="item.accounts_count" class="text-indigo-600 dark:text-indigo-400 font-medium" x-text="item.accounts_count + ' akun'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- If System Type: Protected / Locked -->
                                        <div x-show="item.is_system || item.user_id === null" class="px-2 py-1 text-[10px] text-zinc-400 dark:text-zinc-500 flex items-center gap-1 select-none" title="Tipe bawaan sistem tidak dapat dihapus">
                                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            <span class="hidden sm:inline text-[10px] text-zinc-400">Terkunci</span>
                                        </div>

                                        <!-- If Custom Type: Can Edit & Delete -->
                                        <template x-if="!item.is_system && item.user_id !== null">
                                            <div class="flex items-center gap-1">
                                                <button type="button" @click="editType(item)" class="p-1.5 text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg transition-colors cursor-pointer" title="Edit Tipe">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                <button type="button" @click="deleteType(item)" class="p-1.5 text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition-colors cursor-pointer" title="Hapus Tipe">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <div x-show="typeList.length === 0" class="text-center py-6 text-xs text-zinc-400">
                                Belum ada tipe akun khusus.
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-5 py-3.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between flex-shrink-0 bg-zinc-50/50 dark:bg-zinc-900">
                        <span class="text-[11px] text-zinc-500 dark:text-zinc-400">
                            Total: <strong class="text-zinc-800 dark:text-zinc-200" x-text="typeList.length"></strong> tipe akun
                        </span>
                        <button type="button" @click="openModal = false" class="px-4 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-xl border border-zinc-200 dark:border-zinc-700 transition-colors shadow-2xs cursor-pointer">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </template>

    </div>

    <!-- Delete Confirmation Modal -->
    <x-confirm-delete-modal title="Hapus Akun Kas"
        message="Apakah Anda yakin ingin menghapus akun kas ini? Data transaksi terkait akan tetap tersimpan." />

    @push('scripts')
    <script>
        function accountTypesManager() {
            return {
                openModal: false,
                saving: false,
                editingId: null,
                typeName: '',
                typeCode: '',
                typeList: @json($accountTypes ?? []),
                loadTypes() {
                    fetch('{{ route('admin.cash_account_types.index') }}')
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.typeList = data.data;
                            }
                        });
                },
                resetForm() {
                    this.editingId = null;
                    this.typeName = '';
                    this.typeCode = '';
                },
                editType(item) {
                    this.editingId = item.id;
                    this.typeName = item.name;
                    this.typeCode = item.code;
                },
                saveType() {
                    if (!this.typeName.trim()) return;
                    this.saving = true;

                    const url = this.editingId 
                        ? '{{ url('admin/cash_account_types') }}/' + this.editingId 
                        : '{{ route('admin.cash_account_types.store') }}';
                    const method = this.editingId ? 'PUT' : 'POST';

                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.typeName,
                            code: this.typeCode
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.saving = false;
                        if (data.success) {
                            if (typeof showToast === 'function') {
                                showToast(data.message || 'Tipe akun berhasil disimpan.', 'success');
                            }
                            this.resetForm();
                            this.loadTypes();
                            if (window.Livewire) {
                                window.Livewire.dispatch('pg:eventRefresh-cashAccount-table');
                            }
                        } else {
                            if (typeof showToast === 'function') {
                                showToast(data.message || 'Gagal menyimpan tipe akun.', 'error');
                            } else {
                                alert(data.message || 'Gagal menyimpan tipe akun.');
                            }
                        }
                    })
                    .catch(err => {
                        this.saving = false;
                        if (typeof showToast === 'function') {
                            showToast('Terjadi kesalahan sistem.', 'error');
                        } else {
                            alert('Terjadi kesalahan.');
                        }
                    });
                },
                deleteType(item) {
                    if (!confirm('Hapus tipe akun "' + item.name + '"?')) return;
                    fetch('{{ url('admin/cash_account_types') }}/' + item.id, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof showToast === 'function') {
                                showToast(data.message || 'Tipe akun berhasil dihapus.', 'success');
                            }
                            this.loadTypes();
                        } else {
                            if (typeof showToast === 'function') {
                                showToast(data.message || 'Gagal menghapus tipe akun.', 'error');
                            } else {
                                alert(data.message || 'Gagal menghapus tipe akun.');
                            }
                        }
                    })
                    .catch(err => {
                        if (typeof showToast === 'function') {
                            showToast('Terjadi kesalahan sistem.', 'error');
                        } else {
                            alert('Terjadi kesalahan.');
                        }
                    });
                }
            }
        }
    </script>
    @endpush
@endsection
