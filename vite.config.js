import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin.index.js',
                'resources/js/teachers.index.js',
                'resources/js/parents.index.js',
                'resources/js/students.index.js',
                
                'resources/js/utils.js',
                'resources/js/document.js',
                
                'resources/css/admin.index.css',
                'resources/css/document.css',
            ],
            refresh: true,
        }),
    ],
});
