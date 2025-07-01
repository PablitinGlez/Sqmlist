import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // --- AGREGA ESTAS LÍNEAS PARA FILAMENT ---
        './app/Filament/**/*.php', // Escanea tus archivos de recursos de Filament
        './resources/views/filament/**/*.blade.php', // Si tienes vistas Blade personalizadas para Filament
        './vendor/filament/**/*.blade.php', // Vistas Blade internas de Filament
        './vendor/filament/**/*.php', // Clases PHP de Filament que puedan contener HTML (como Placeholders)
        // --- FIN DE LAS LÍNEAS A AGREGAR ---
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};