/** @type {import('tailwindcss').Config} */
const plugin = require('./node_modules/tailwindcss/plugin');
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    container: {
      center: true,
    },
    fontFamily: {
      sans: ['"Lato"', 'sans-serif']
    },
    extend: {
      backgroundImage: {
        'bodybg': "url('../../public/images/bg-sec.jpg')",
        'hero': "url('../../public/images/bg-slider-new.jpg')",
        'left_life': "url('../../public/images/left-Layer.jpg')",
        'right_life': "url('../../public/images/right-Layer.jpg')",
      },
      colors: {
        primary: {
          400: '#a77c00',
          500: '#906b00',
        },
        yellow:{
          500: '#ffd24d',
        },
        dark: '#252525',
        gray: {
          500: '#666666',
          700: '#333333',
        },
      },
      spacing: {
        '90': '90px',
        '70': '70px',
        '40': '40em',
        '30': '30em',
        '38': '38em',
      }
    },
  },
  variants: {
    extend: {
        backgroundColor: ['label-checked'], // you need add new variant to a property you want to extend
    },
},
  plugins: [ 
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    plugin(({ addVariant, e }) => {
        addVariant('label-checked', ({ modifySelectors, separator }) => {
            modifySelectors(
                ({ className }) => {
                    const eClassName = e(`label-checked${separator}${className}`); // escape class
                    const yourSelector = 'input[type="radio"]'; // your input selector. Could be any
                    return `${yourSelector}:checked ~ .${eClassName}`; // ~ - CSS selector for siblings
                }
            )
        })
    }),
    function ({ addComponents }) {
      addComponents({
        '.container': {
          maxWidth: '100%',
          '@screen sm': {
            maxWidth: '640px',
          },
          '@screen md': {
            maxWidth: '768px',
          },
          '@screen lg': {
            maxWidth: '1140px',
          },
          '@screen xl': {
            maxWidth: '1140px',
          },
        }
      })
    },
],
}
