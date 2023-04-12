import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'

const path = require('path')

export default defineConfig(async ({ command, mode }) => {
  const plugins = [
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
  ]

  if (mode !== 'production') {
    const eslintPlugin = await (await import('vite-plugin-eslint')).default.default

    plugins.push(
      eslintPlugin({
        failOnError: command === 'build',
      })
    )
  }

  return {
    server: {
      hmr: {
        host: 'localhost',
      },
    },
    plugins,
    resolve: {
      alias: {
        ziggy: path.resolve('vendor/tightenco/ziggy/dist'),
      },
    },
    ssr: {
      noExternal: ['laravel-vite-plugin', '@inertiajs/vue3/server'],
    },
    optimizeDeps: {
      include: ['@inertiajs/vue3', 'axios', 'vue', 'ziggy', 'pinia'],
    },
  }
})
