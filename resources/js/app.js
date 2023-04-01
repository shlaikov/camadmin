import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createPinia } from 'pinia'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import mixinBase from './base'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Camadmin'
/* global Ziggy */
createInertiaApp({
  title: (title) => `${title} | ${appName}`,
  progress: {
    color: '#4c1d95',
  },
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .use(createPinia())
      .mixin(mixinBase)
      .mount(el)
  },
})
