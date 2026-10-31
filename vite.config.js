import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            hmr: {
                host: process.env.VITE_HOST || 'localhost',
            },
        }),
    ],
    base: process.env.VITE_ASSET_URL || '/build/',
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
