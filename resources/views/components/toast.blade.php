@php
    $toastSuccess = session('success');
    $toastError = session('error');
    $toastInfo = session('info');
    $toastWarning = session('warning');

    if ($toastSuccess || $toastError || $toastInfo || $toastWarning) {
        session()->forget(['success', 'error', 'info', 'warning']);
        session()->save();
    }
@endphp

<!-- Theme Toast Integration Component -->
<script>
(function() {
    // Clear any standing toasts from previous page views immediately on new page load!
    const toastContainer = document.getElementById('toast-container');
    if (toastContainer) {
        toastContainer.innerHTML = '';
    }

    function fireThemeToast(message, type) {
        if (!message) return;

        function run() {
            if (typeof showToast === 'function') {
                showToast(message, type);
            } else if (window.Toast && typeof window.Toast.fire === 'function') {
                window.Toast.fire({ icon: type, title: message });
            }
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            run();
        } else {
            document.addEventListener('DOMContentLoaded', run);
        }
    }

    // Clean DOM toast elements if page is restored from browser BFCache (Back button)
    window.addEventListener('pageshow', function(event) {
        const container = document.getElementById('toast-container');
        if (container && event.persisted) {
            container.innerHTML = '';
        }
    });

    @if($toastSuccess)
        fireThemeToast({!! json_encode($toastSuccess) !!}, 'success');
    @endif
    @if($toastError)
        fireThemeToast({!! json_encode($toastError) !!}, 'error');
    @endif
    @if($toastInfo)
        fireThemeToast({!! json_encode($toastInfo) !!}, 'info');
    @endif
    @if($toastWarning)
        fireThemeToast({!! json_encode($toastWarning) !!}, 'warning');
    @endif

    // Listen for custom Livewire / Alpine 'notify' events
    window.addEventListener('notify', function(e) {
        let detail = e.detail;
        if (Array.isArray(detail) && detail.length > 0) {
            detail = detail[0];
        } else if (detail && detail.type === undefined && detail[0] !== undefined) {
            detail = detail[0];
        }
        
        var msg = (detail && (detail.message || detail.title)) ? (detail.message || detail.title) : '';
        var t = (detail && detail.type) ? detail.type : 'info';
        
        fireThemeToast(msg, t);
    });
})();
</script>
