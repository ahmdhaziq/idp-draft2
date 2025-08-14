import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react'; 

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/js/App.tsx'],
            refresh: true,
        }),
        tailwindcss(),
        react(),

    ],
     // If you develop inside Docker, uncomment this:
   server: { host: '0.0.0.0', port: 5173, hmr: { host: 'localhost' } },
});
