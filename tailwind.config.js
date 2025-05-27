const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'primary': '#FFA500',    // Orange claire
                'secondary': '#FFFFFF',   // Blanc
                'accent': '#0000FF',     // Bleu
                'dark': '#000000',       // Noir
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
