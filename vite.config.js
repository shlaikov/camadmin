import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import eslintPlugin from 'vite-plugin-eslint'

const path = require('path')

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
  resolve: {
    alias: {
      ziggy: path.resolve('vendor/tightenco/ziggy/dist'),
    },
  },
  ssr: {
    noExternal: ['@inertiajs/vue3/server'],
  },
  optimizeDeps: {
    include: ['@inertiajs/vue3', 'axios', 'vue', 'ziggy'],
  },
}))
