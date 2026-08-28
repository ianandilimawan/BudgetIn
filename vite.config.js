import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/landing.js',
                'resources/js/form-libs.js',
                'resources/js/filepond-lib.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        chunkSizeWarningLimit: 1600,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('tinymce')) {
                            return 'vendor-tinymce';
                        }
                        if (id.includes('apexcharts')) {
                            return 'vendor-apexcharts';
                        }
                        if (id.includes('filepond')) {
                            return 'vendor-filepond';
                        }
                        if (id.includes('sweetalert2')) {
                            return 'vendor-sweetalert2';
                        }
                    }
                },
            },
        },
    },
});
