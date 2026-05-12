import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/auth.css',
                'resources/js/app.js',
                'resources/css/welcome.css',
                'resources/js/welcome.js',
                'resources/css/menu-public.css',
                'resources/js/menu-public.js',
                'resources/css/dashboard.css',
                'resources/js/dashboard.js',
            ],
            refresh: true,
        }),
    ],
});
