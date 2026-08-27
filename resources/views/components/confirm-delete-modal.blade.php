@props([
    'id' => 'deleteModal',
    'title' => 'Confirm Delete',
    'message' => 'Are you sure you want to delete this item? This action cannot be undone.',
])

<script type="module">
    document.addEventListener('alpine:init', () => {
        window.addEventListener('open-delete-modal', (e) => {
            const actionUrl = e.detail.action;
            const isDarkMode = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: '<div class="mx-auto w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-3"><svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>{{ $title }}',
                html: '{{ $message }}',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                buttonsStyling: false,
                background: isDarkMode ? '#18181b' : '#ffffff',
                customClass: {
                    popup: 'border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xl',
                    title: '!text-lg font-bold tracking-tight mt-2 !text-zinc-900 dark:!text-white',
                    htmlContainer: 'text-sm !text-zinc-500 dark:!text-zinc-400 mt-2 mb-6',
                    actions: 'flex gap-4 w-full justify-center mt-6',
                    confirmButton: 'btn btn-danger btn-md px-6',
                    cancelButton: 'btn btn-secondary btn-md px-6'
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
                title: '<div class="mx-auto w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-3"><svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>Confirm Bulk Delete',
                html: 'Are you sure you want to delete ' + ids.length + ' selected items? This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel',
                buttonsStyling: false,
                background: isDarkMode ? '#18181b' : '#ffffff',
                customClass: {
                    popup: 'border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xl',
                    title: '!text-lg font-bold tracking-tight mt-2 !text-zinc-900 dark:!text-white',
                    htmlContainer: 'text-sm !text-zinc-500 dark:!text-zinc-400 mt-2 mb-6',
                    actions: 'flex gap-4 w-full justify-center mt-6',
                    confirmButton: 'btn btn-danger btn-md px-6',
                    cancelButton: 'btn btn-secondary btn-md px-6'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('bulkDeleteConfirmed', { ids: ids, model: model });
                }
            });
        });
    });
</script>
