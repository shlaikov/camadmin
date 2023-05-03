<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Navbar from './Partials/Navbar.vue'

defineProps({
  instanceId: {
    required: true,
    type: String,
  },
})
</script>

<template>
  <AppLayout title="Incedents">
    <template #header>
      <Navbar :instance-id="instanceId" />
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- <Welcome /> -->
        </div>
      </div>
    </div>
  </AppLayout>
</template>
