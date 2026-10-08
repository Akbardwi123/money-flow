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
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                brand: {
                    dark: '#070a13',
                    card: '#0e1424',
                    border: '#1a233d',
                    emerald: '#10b981',
                    cyan: '#06b6d4',
                    indigo: '#6366f1',
                },
            },
            boxShadow: {
                'glow-emerald': '0 0 30px -5px rgba(16, 185, 129, 0.35)',
                'glow-cyan': '0 0 30px -5px rgba(6, 182, 212, 0.35)',
                'glow-indigo': '0 0 30px -5px rgba(99, 102, 241, 0.35)',
                'fintech-card': '0 10px 40px -10px rgba(0, 0, 0, 0.5)',
            },
        },
    },

    plugins: [forms],
};
