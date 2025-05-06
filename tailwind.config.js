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
            animation: {
                'shine': 'shine 3s ease-in-out infinite',
                'swipe': 'swipe 3s ease-in-out infinite alternate',
                'orbit': 'orbit 8s linear infinite',
                'ping-slow': 'ping 3s cubic-bezier(0, 0, 0.2, 1) infinite',
                'spin-slow': 'spin 8s linear infinite',
                'fade-in-down': 'fadeInDown 1s ease-out forwards',
            },
            keyframes: {
                shine: {
                  '0%': { transform: 'translateX(-100%)' },
                  '100%': { transform: 'translateX(100%)' },
                },
                swipe: {
                  '0%': { transform: 'translateX(-100%)' },
                  '100%': { transform: 'translateX(100%)' },
                },
                orbit: {
                  '0%': { transform: 'rotate(0deg) translateX(40px) rotate(0deg)' },
                  '100%': { transform: 'rotate(360deg) translateX(40px) rotate(-360deg)' },
                },
                fadeInDown: {
                  '0%': { opacity: '0', transform: 'translateY(-10px)' },
                  '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            boxShadow: {
                'glow': '0 0 8px 0 rgba(59, 130, 246, 0.6)',
            },
            transitionDelay: {
                '75': '75ms',
                '100': '100ms',
                '150': '150ms',
                '200': '200ms',
                '300': '300ms',
                '400': '400ms',
                '500': '500ms',
                '700': '700ms',
            },
        },
    },

    plugins: [forms],
};
