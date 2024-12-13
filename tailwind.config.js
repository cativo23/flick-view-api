import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'tokyo-night': {
                    DEFAULT: '#1a1b26',
                    primary: '#7aa2f7',   // Azul como color primario
                    secondary: '#9ece6a', // Verde
                    tertiary: '#f7768e',   // Rojo (se mantiene para el detalle)
                    dark: '#2a2e37',      // Gris oscuro
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
