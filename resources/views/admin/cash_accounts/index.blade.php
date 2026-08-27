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
                    class="px-3.5 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 rounded-xl text-xs sm:text-sm font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm">
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

        <!-- Master Account Types Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openModal" @click="openModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-2xl px-5 pt-5 pb-6 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-zinc-200 dark:border-zinc-800">
                    
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100 dark:border-zinc-800">
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </span>
                                Master Tipe Akun Kas
                            </h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Tambah dan kelola kategori tipe akun sesuai kebutuhan.</p>
                        </div>
                        <button type="button" @click="openModal = false" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Quick Add Form -->
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-xl mb-4 border border-zinc-200/60 dark:border-zinc-700/60">
                        <div class="text-xs font-semibold text-zinc-800 dark:text-zinc-200 mb-2 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            <span x-text="editingId ? 'Edit Tipe Akun' : 'Tambah Tipe Baru'"></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-2.5">
                            <div>
                                <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-400 mb-0.5">Nama Tipe <span class="text-rose-500">*</span></label>
                                <input type="text" x-model="typeName" placeholder="Contoh: Koperasi / Paylater" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-zinc-900 dark:text-white outline-none focus:ring-1 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-400 mb-0.5">Kode (Opsional)</label>
                                <input type="text" x-model="typeCode" placeholder="otomatis_dari_nama" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg text-zinc-900 dark:text-white outline-none focus:ring-1 focus:ring-indigo-500" />
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" x-show="editingId" @click="resetForm()" class="px-3 py-1 text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                                Batal Edit
                            </button>
                            <button type="button" @click="saveType()" :disabled="saving || !typeName" class="px-3.5 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 rounded-lg shadow-sm transition-all inline-flex items-center gap-1">
                                <span x-text="saving ? 'Menyimpan...' : (editingId ? 'Update Tipe' : 'Tambah Tipe')"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Types List -->
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        <template x-for="item in typeList" :key="item.id">
                            <div class="flex items-center justify-between p-2.5 bg-white dark:bg-zinc-800/80 border border-zinc-200/80 dark:border-zinc-700/80 rounded-xl hover:border-zinc-300 dark:hover:border-zinc-600 transition-colors">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-zinc-600 dark:text-zinc-300 font-bold text-xs">
                                        <span x-text="item.name.substring(0, 1).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-zinc-900 dark:text-white" x-text="item.name"></div>
                                        <div class="text-[10px] text-zinc-500 dark:text-zinc-400 flex items-center gap-2">
                                            <span>Kode: <code class="bg-zinc-100 dark:bg-zinc-900 px-1 py-0.5 rounded text-[9px]" x-text="item.code"></code></span>
                                            <span x-show="item.accounts_count" class="text-indigo-600 dark:text-indigo-400 font-medium" x-text="item.accounts_count + ' akun'"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="editType(item)" class="p-1.5 text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button type="button" @click="deleteType(item)" class="p-1.5 text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <div x-show="typeList.length === 0" class="text-center py-4 text-xs text-zinc-400">
                            Belum ada tipe akun.
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end">
                        <button type="button" @click="openModal = false" class="px-4 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-xl transition-colors">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>

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
                            this.resetForm();
                            this.loadTypes();
                            if (window.Livewire) {
                                window.Livewire.dispatch('pg:eventRefresh-cashAccount-table');
                            }
                        } else {
                            alert(data.message || 'Gagal menyimpan tipe akun.');
                        }
                    })
                    .catch(err => {
                        this.saving = false;
                        alert('Terjadi kesalahan.');
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
                            this.loadTypes();
                        } else {
                            alert(data.message || 'Gagal menghapus tipe akun.');
                        }
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan.');
                    });
                }
            }
        }
    </script>
    @endpush
@endsection
