import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',  'vendor/s-webs/s-files/resources/js/filemanager.js',],
            refresh: true,
        }),
    ],
});
