import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [
        vue(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            // @ now points to front/src/ — fully self-contained
            '@': fileURLToPath(new URL('./src', import.meta.url)),
        },
    },
    server: {
        host:  '0.0.0.0',
        port:  5173,
        // Proxy /api to the backend container (set VITE_API_TARGET in .env)
        proxy: {
            '/api': {
                target:       process.env.VITE_API_TARGET ?? 'http://localhost:8000',
                changeOrigin: true,
            },
        },
    },
    build: {
        outDir:      'dist',
        emptyOutDir: true,
    },
});
