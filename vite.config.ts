import { defineConfig } from 'vite'

export default defineConfig({
  root: '.',
  publicDir: 'public',
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
    rollupOptions: {
      input: {
        main: 'index.html',
        pages: 'src/styles/pages.scss',
      },
      output: {
        assetFileNames: (assetInfo) => {
          if (assetInfo.name === 'pages.css') return 'assets/pages.css';
          return 'assets/[name]-[hash][extname]';
        },
      },
    },
  },
  server: {
    port: 3000,
    open: true,
  },
})
