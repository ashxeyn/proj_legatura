import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // include app entry plus admin assets we added so manifest contains them
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/admin/home/mainComponents.css',
                'resources/js/admin/home/mainComponents.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
