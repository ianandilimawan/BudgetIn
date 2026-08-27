<style>
    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select {
        padding: 0.5rem 2rem 0.5rem 0.75rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        background-color: white;
    }

    .dark .dataTables_wrapper .dataTables_length select {
        border-color: #4b5563;
        background-color: #1f2937;
        color: #f9fafb;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        margin-left: 0.5rem;
    }

    .dark .dataTables_wrapper .dataTables_filter input {
        border-color: #4b5563;
        background-color: #1f2937;
        color: #f9fafb;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        padding: 1.5rem 1.5rem 1rem 1.5rem;
    }

    .dataTables_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding: 1rem 1.5rem;
    }

    .dataTables_wrapper .dataTables_info {
        padding: 1rem 1.5rem;
    }

    .dataTables_wrapper {
        font-size: 0.875rem;
    }

    /* Horizontal scroll styling */
    .dataTables_wrapper .dataTables_scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .dataTables_wrapper .dataTables_scrollBody {
        overflow-x: auto;
        overflow-y: visible;
    }

    /* Ensure table doesn't break on small screens */
    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Style scrollbar for better UX */
    .table-container::-webkit-scrollbar,
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar {
        height: 8px;
    }

    .table-container::-webkit-scrollbar-track,
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .dark .table-container::-webkit-scrollbar-track,
    .dark .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #374151;
    }

    .table-container::-webkit-scrollbar-thumb,
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-container::-webkit-scrollbar-thumb:hover,
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .dark .table-container::-webkit-scrollbar-thumb,
    .dark .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #6b7280;
    }

    .dark .table-container::-webkit-scrollbar-thumb:hover,
    .dark .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>
