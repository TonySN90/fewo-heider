import flowbitePlugin from "flowbite/plugin";
import flowbite from "flowbite-react/tailwind";

export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
    "node_modules/flowbite-react/lib/esm/**/*.js",
    flowbite.content(),
  ],
  theme: {
    extend: {
      colors: {
        color_bg_lightgray: "#fefefe",
        color_bg_darkgray: "#6B7280",
      },
    },
  },
  plugins: [flowbitePlugin],
};
