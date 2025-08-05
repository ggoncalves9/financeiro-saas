import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': 'node_modules/bootstrap',
            '~@fortawesome': 'node_modules/@fortawesome',
        }
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['bootstrap', 'chart.js', 'sweetalert2'],
                    fontawesome: ['@fortawesome/fontawesome-free']
                }
            }
        }
    }
});
