/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        primary: { DEFAULT: '#16A34A' },
        secondary: { DEFAULT: '#22C55E' },
        accent: { DEFAULT: '#2563EB' },
        'neutral-light': { DEFAULT: '#F8FAFC' },
        'neutral-white': { DEFAULT: '#FFFFFF' },
        'neutral-gray': { DEFAULT: '#64748B' },
        error: { DEFAULT: '#EF4444' },
      },
      fontFamily: {
        sans: ['Inter', 'Source Sans Pro', 'Arial', 'sans-serif'],
      },
      spacing: {
        '1': '0.25rem',   // 4px base unit
        '2': '0.5rem',    // 8px base unit
        '3': '0.75rem',
        '4': '1rem',
        '5': '1.25rem',
        '6': '1.5rem',
        '8': '2rem',
        '12': '3rem',
        '14': '3.5rem',
        '16': '4rem',
        '18': '4.5rem',
      },
      borderRadius: {
        'lg': '12px',
        'md': '8px',
        DEFAULT: '8px',
      },
      boxShadow: {
        'card': '0 2px 8px rgba(22, 163, 74, 0.08)',
        'focus-ring': '0 0 0 2px #2563EB', // accent blue focus ring
      },
      fontSize: {
        display: ['2.5rem', { lineHeight: '1.2' }], // 40px
        headline: ['2rem', { lineHeight: '1.3' }], // 32px
        subheadline: ['1.25rem', { lineHeight: '1.4' }], // 20px
        body: ['1rem', { lineHeight: '1.5' }], // 16px
        small: ['0.875rem', { lineHeight: '1.4' }], // 14px
      },
    },
  },
  plugins: [],
}