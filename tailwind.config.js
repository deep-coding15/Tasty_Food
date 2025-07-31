/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    ".*/*.{html,js,php}",
    "./app/vues/**/*.php",
    "./app/vues/**/**/*.php",
    "./public/**/*.php",
    "./public/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#EF4444',     // rouge tomate vif
        secondary: '#FBBF24',   // jaune moutarde chaleureux
        accent: '#10B981',      // vert frais
        background: '#FFF7ED',  // crème doux pour fond
        text: '#374151',        // gris foncé pour le texte
      },
      fontFamily: {
        sans: ['"Poppins"', 'Arial', 'sans-serif'], // police moderne et lisible
      },
      spacing: {
        '72': '18rem',
        '84': '21rem',
        '96': '24rem',
      },
      borderRadius: {
        'xl': '1rem',
      }
    },
  },
  plugins: [
    function ({ addUtilities }) {
      addUtilities({
        '.scrollbar-hide': {
          /* Chrome, Safari and Opera */
          '&::-webkit-scrollbar': {
            display: 'none',
          },
          /* Firefox */
          'scrollbar-width': 'none',
          /* IE 10+ */
          '-ms-overflow-style': 'none',
        },
      });
    },],
}