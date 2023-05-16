<script setup>
import { computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'

import Navigation from './Navigation.vue'
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'
import JetBanner from '@/Components/Banner.vue'
import { useInstanceStore } from '@/Stores/instance'

const instanceStore = useInstanceStore()

const instances = computed(() => instanceStore.instances)
const isMounted = computed(() => instanceStore.mounted)

defineProps({
  title: String,
})

onMounted(() => {
  if (instances.value && !isMounted.value) {
    instances.value.forEach(async (instance) => {
      await instanceStore.fetchDefenitions(instance.id)
      await instanceStore.fetchTaskStatistics(instance.id)
    })

    instanceStore.$patch((state) => {
      state.mounted = true
    })
  }
})
</script>

<template>
  <div>
    <Head :title="title" />

    <JetBanner />

    <Sidebar />

    <div class="flex flex-col min-h-screen bg-gray-100 justify-between md:ml-64">
      <Navigation>
        <div v-if="$slots.header" class="flex mx-auto h-full items-center">
          <slot name="header" />
        </div>
      </Navigation>

      <!-- Page Heading -->

      <!-- Page Content -->
      <main class="mb-auto min-h-max">
        <slot />
      </main>

      <!-- Page Footer -->
      <Footer />
    </div>
  </div>
</template>
