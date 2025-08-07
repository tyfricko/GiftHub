import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

export default defineConfig({
    server: {
        https: {
            key: fs.readFileSync('C:/laragon/etc/ssl/laragon.key'),
            cert: fs.readFileSync('C:/laragon/etc/ssl/laragon.crt'),
        },
        host: 'gifthub.test',
        cors: true
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true
        }),
    ],
    build: {
        manifest: true,
        rollupOptions: {
            output: {
                assetFileNames: 'assets/[name].[hash][extname]',
                chunkFileNames: 'assets/[name].[hash].js',
                entryFileNames: 'assets/[name].[hash].js',
            }
        }
    },
    optimizeDeps: {
        include: ['axios'],
    }
});
