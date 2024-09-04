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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
    safelist: [
        'bg-blue-500',
        'bg-yellow-600',
        'bg-orange-600',
        'bg-lime-600',
        'bg-green-600',
        'text-blue-100',
        'text-2x1',
        'rounded-md',
        'rounded-none'
    ],
};
