import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    900: '#0c4a6e',
                },
                theme: {
                    primary: 'var(--color-primary, #0284c7)',
                    'primary-hover': 'var(--color-primary-hover, #0369a1)',
                    secondary: 'var(--color-secondary, #0f172a)',
                    accent: 'var(--color-accent, #f59e0b)',
                    text: 'var(--color-text, #1e293b)',
                    bg: 'var(--color-bg, #f8fafc)',
                }
            },
            fontFamily: {
                sans: ['var(--font-family)', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
