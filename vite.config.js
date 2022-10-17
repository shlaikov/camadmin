import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import eslintPlugin from 'vite-plugin-eslint'

export default defineConfig(({ command }) => ({
  server: {
    hmr: {
      host: 'localhost',
    },
  },
  plugins: [
    eslintPlugin({
      failOnError: command === 'build',
    }),
    laravel({
      input: 'resources/js/app.js',
      ssr: 'resources/js/ssr.js',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  ssr: {
    noExternal: ['@inertiajs/server'],
  },
}))
