@props([
    'id' => 'deleteModal',
    'title' => 'Konfirmasi Hapus',
    'message' => 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
])

<script type="module">
    document.addEventListener('alpine:init', () => {
        window.addEventListener('open-delete-modal', (e) => {
            const actionUrl = e.detail?.action || e.detail;
            const isDarkMode = document.documentElement.classList.contains('dark');
            const customTitle = e.detail?.title || '{{ $title }}';
            const customMessage = e.detail?.message || '{{ $message }}';

            Swal.fire({
                title: '<div class="mx-auto w-9 h-9 sm:w-10 sm:h-10 bg-rose-50 dark:bg-rose-950/60 rounded-xl flex items-center justify-center mb-2 text-rose-600 dark:text-rose-400"><svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div><span class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white leading-tight block">' + customTitle + '</span>',
                html: '<span class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed block px-1">' + customMessage + '</span>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                background: isDarkMode ? '#18181b' : '#ffffff',
                customClass: {
                    popup: '!p-4 sm:!p-6 !max-w-[330px] sm:!max-w-md !w-[90%] border border-zinc-200/80 dark:border-zinc-800 rounded-2xl sm:rounded-3xl shadow-xl',
                    title: '!p-0 !m-0',
                    htmlContainer: '!p-0 !m-0 !mt-1.5 !mb-4',
                    actions: '!flex !flex-col-reverse sm:!flex-row !gap-2 sm:!gap-2.5 !w-full !p-0 !m-0 !mt-4',
                    confirmButton: '!w-full sm:!w-auto !flex-1 !px-4 !py-2 sm:!py-2.5 !text-xs sm:!text-sm !font-semibold !rounded-xl !bg-rose-600 hover:!bg-rose-700 !text-white !transition !shadow-xs !m-0 cursor-pointer',
                    cancelButton: '!w-full sm:!w-auto !flex-1 !px-4 !py-2 sm:!py-2.5 !text-xs sm:!text-sm !font-semibold !rounded-xl !bg-zinc-100 dark:!bg-zinc-800 hover:!bg-zinc-200 dark:hover:!bg-zinc-700 !text-zinc-700 dark:!text-zinc-300 !transition !m-0 cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form dynamically
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = actionUrl;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        window.addEventListener('confirm-bulk-delete', (e) => {
            const data = e.detail[0] || e.detail;
            const ids = data.ids;
            const model = data.model;
            const isDarkMode = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: '<div class="mx-auto w-9 h-9 sm:w-10 sm:h-10 bg-rose-50 dark:bg-rose-950/60 rounded-xl flex items-center justify-center mb-2 text-rose-600 dark:text-rose-400"><svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div><span class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white leading-tight block">Hapus Masal Data</span>',
                html: '<span class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed block px-1">Apakah Anda yakin ingin menghapus ' + ids.length + ' data terpilih? Tindakan ini tidak dapat dibatalkan.</span>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                background: isDarkMode ? '#18181b' : '#ffffff',
                customClass: {
                    popup: '!p-4 sm:!p-6 !max-w-[330px] sm:!max-w-md !w-[90%] border border-zinc-200/80 dark:border-zinc-800 rounded-2xl sm:rounded-3xl shadow-xl',
                    title: '!p-0 !m-0',
                    htmlContainer: '!p-0 !m-0 !mt-1.5 !mb-4',
                    actions: '!flex !flex-col-reverse sm:!flex-row !gap-2 sm:!gap-2.5 !w-full !p-0 !m-0 !mt-4',
                    confirmButton: '!w-full sm:!w-auto !flex-1 !px-4 !py-2 sm:!py-2.5 !text-xs sm:!text-sm !font-semibold !rounded-xl !bg-rose-600 hover:!bg-rose-700 !text-white !transition !shadow-xs !m-0 cursor-pointer',
                    cancelButton: '!w-full sm:!w-auto !flex-1 !px-4 !py-2 sm:!py-2.5 !text-xs sm:!text-sm !font-semibold !rounded-xl !bg-zinc-100 dark:!bg-zinc-800 hover:!bg-zinc-200 dark:hover:!bg-zinc-700 !text-zinc-700 dark:!text-zinc-300 !transition !m-0 cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('bulkDeleteConfirmed', { ids: ids, model: model });
                }
            });
        });
    });
</script>
