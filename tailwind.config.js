/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./templates/**/*.php', './public/assets/*.js', './src/**/*.php'],
  theme: {
    extend: {
      colors: {
        // Koyu Vitrin paleti (genel site)
        bg: '#141210',
        surface: '#1B1815',
        surface2: '#232019',
        line: '#2E2A24',
        ink: { DEFAULT: '#EFE7DA', dim: '#A9A093' },
        gold: { DEFAULT: '#C9A227', light: '#E3C25E', dark: '#9E7D1B' },

        // Eski tokenlar — yönetim paneli bunları kullanıyor, korunuyor
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
      // Mobilde yapışkan alt çubuğun altında kalan güvenli alan
      spacing: { safe: 'env(safe-area-inset-bottom, 0px)' },
      keyframes: {
        kenburns: { '0%': { transform: 'scale(1)' }, '100%': { transform: 'scale(1.08)' } },
        fadeUp: { '0%': { opacity: 0, transform: 'translateY(24px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
      },
      animation: {
        kenburns: 'kenburns 22s ease-out forwards',
        fadeUp: 'fadeUp .9s cubic-bezier(.22,1,.36,1) both',
      },
    },
  },
  plugins: [],
};
