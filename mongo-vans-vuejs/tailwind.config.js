/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/**/*.html',
    './src/**/*.{js,jsx,ts,tsx,vue}',
  ],
  theme: {
    container: {
      center: true,
      // margin: 0,
    },
    extend: {
      colors: {
        primary: "#3060FF",
        white: "#ffffff",
        black: "#000000",
        red: "#ed174f",
        yellow: "#CA9C1C",
        orange: "#FF8C00"
      },
      minWidth: {
        'label': '150px',
      },
      margin: {
        'fromLabel': '150px'
      }
    },
  },
  plugins: [],
}

