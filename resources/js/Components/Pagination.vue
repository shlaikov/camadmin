<script>
import { defineComponent } from 'vue'
import { Link as InertiaLink } from '@inertiajs/vue3'

export default defineComponent({
  components: {
    InertiaLink,
  },
  props: {
    links: Array,
  },
})
</script>
<!-- eslint-disable vue/no-v-text-v-html-on-component -->
<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap mt-8">
      <template v-for="(link, key) in links" :key="key">
        <div
          v-if="link.url === null"
          class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
          v-html="link.label"
        />

        <InertiaLink
          v-else
          class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary"
          :class="{ 'bg-violet-500 text-white': link.active }"
          :href="link.url"
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>
