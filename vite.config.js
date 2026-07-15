import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const isProduction = process.env.NODE_ENV === 'production';

export default defineConfig({
    base: isProduction ? '/nobar/build/' : '/',
    build: {
        manifest: 'manifest.json',
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                assetFileNames: 'assets/[name]-[hash][extname]',
                entryFileNames: 'assets/[name]-[hash].js',
            },
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
