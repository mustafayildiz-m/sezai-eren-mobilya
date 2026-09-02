/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./templates/**/*.php', './public/assets/*.js', './src/**/*.php'],
  theme: {
    extend: {
      colors: {
        walnut: '#2B1D14',
        ground: '#1A130E',
        cream: '#F5EFE6',
        copper: { DEFAULT: '#B87333', light: '#D4956A', dark: '#8F5824' },
        beige: '#E8DCC8',
        sand: '#CBB99E',
      },
      fontFamily: {
        serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      letterSpacing: { widest2: '0.25em' },
      keyframes: {
        kenburns: { '0%': { transform: 'scale(1) translate(0,0)' }, '100%': { transform: 'scale(1.12) translate(-1.5%, -1%)' } },
        fadeUp: { '0%': { opacity: 0, transform: 'translateY(28px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
        marquee: { '0%': { transform: 'translateX(0)' }, '100%': { transform: 'translateX(-50%)' } },
        shimmer: { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
      },
      animation: {
        kenburns: 'kenburns 18s ease-out forwards',
        fadeUp: 'fadeUp .9s cubic-bezier(.22,1,.36,1) both',
        marquee: 'marquee 40s linear infinite',
      },
    },
  },
  plugins: [],
};
