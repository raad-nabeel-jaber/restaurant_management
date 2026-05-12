import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import flowbitePlugin from 'flowbite/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                cairo: ['Cairo', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    400: '#fbbf24',
                    500: '#f5a623',
                    600: '#d97706',
                    700: '#b45309',
                },
                dark: {
                    700: '#1e2028',
                    800: '#17191f',
                    900: '#0f1014',
                    950: '#0a0b0d',
                },
                menusnap: {
                    text: '#f0ece3',
                    muted: '#55524f',
                    dim: '#7a7875',
                },
            },
        },
    },

    plugins: [forms, flowbitePlugin],
};
