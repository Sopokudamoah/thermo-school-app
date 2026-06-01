/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
        fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            heading: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
            mono: ['"JetBrains Mono"', 'monospace'],
        },
        colors: {
            indigo: {
                50:  '#EEF2FF',
                100: '#E0E7FF',
                200: '#C7D2FE',
                500: '#6366F1',
                600: '#4F46E5',
                700: '#4338CA',
                800: '#3730A3',
            },
        },
        boxShadow: {
            'card': '0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04)',
        },
        borderRadius: {
            'card': '10px',
            'modal': '14px',
        },
    },
  },
  plugins: [],
}
