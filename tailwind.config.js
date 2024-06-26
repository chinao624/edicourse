/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily:{
        'josefin':["Josefin Sans", ...defaultTheme.fontFamily.sans],
      }
    },
    daisyui: {
      themes: ["cupcake","pastel","lemonade","nord"],
       
    },
  },
  plugins: [require("daisyui")],
}

