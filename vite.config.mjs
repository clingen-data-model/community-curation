import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/admin.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        extensions: ['.mjs', '.js', '.ts', '.jsx', '.tsx', '.json', '.vue'],
    },
    server: { host: 'localhost', port: 5173 },
    optimizeDeps: {
        include: [
            'primevue/config',
            'primevue/tooltip',
            'primevue/button',
            'primevue/calendar',
            'primevue/card',
            'primevue/checkbox',
            'primevue/column',
            'primevue/datatable',
            'primevue/dialog',
            'primevue/inputtext',
            'primevue/menu',
            'primevue/overlaypanel',
            'primevue/paginator',
            'primevue/panel',
            'primevue/progressbar',
            'primevue/radiobutton',
            'primevue/tabpanel',
            'primevue/tabview',
            'primevue/tag',
        ],
    },
});
