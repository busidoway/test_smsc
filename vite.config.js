import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/core.css',
                'resources/css/page-auth.css',
                'resources/css/theme-default.css',
                'resources/js/app.js',
                'resources/fonts/boxicons.scss'
            ],
            refresh: true,
        }),
    ],
});
