import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/main.ts',
        'resources/js/admin-legal.ts',
        'resources/css/main.scss',
        'resources/css/pages.scss',
        'resources/css/admin.scss',
      ],
      refresh: true,
    }),
  ],
  server: {
    host: '0.0.0.0',
    port: 5173,
  },
})
