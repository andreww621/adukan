import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
<<<<<<< HEAD
=======
import { bunny } from 'laravel-vite-plugin/fonts';
>>>>>>> d2ba4ff (Initial commit adukan vercel)
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
<<<<<<< HEAD
=======
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
>>>>>>> d2ba4ff (Initial commit adukan vercel)
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
<<<<<<< HEAD
});
=======
});
>>>>>>> d2ba4ff (Initial commit adukan vercel)
