import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#4B9DCB',
                    50: '#DBEBF5',
                    100: '#C9E3F0',
                    200: '#A7D1E6',
                    300: '#85BFDC',
                    400: '#63ADD2',
                    500: '#4B9DCB',
                    600: '#3A8AB8',
                    700: '#2E7096',
                    800: '#235673',
                    900: '#183C50',
                },
                muted: '#A0BED0',
                surface: {
                    DEFAULT: '#41424C',
                    light: '#5A5B66',
                    dark: '#2E2F36',
                },
                accent: '#4AB7B6',
                danger: '#EA7173',
            },
        },
    },

    plugins: [forms],
};
