import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [vue()],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            input: {
                main: 'resources/js/app.js',
                style: 'resources/css/app.css',
            },
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
