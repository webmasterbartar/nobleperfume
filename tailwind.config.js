module.exports = {
  content: [
    "./**/*.php",
    "./assets/src/js/**/*.js",
    "./assets/src/css/**/*.css",
    "./**/*.html",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["YekanBakhFaNum", "sans-serif"],
        serif: ["Playfair Display", "serif"],
      },
      colors: {
        primary: "#051061",
        accent: "#c8a84b",
      },
    },
  },
  plugins: [],
};
