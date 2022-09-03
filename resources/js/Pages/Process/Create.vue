<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import CreateProcessForm from '@/Pages/Process/Partials/CreateProcessForm.vue'
</script>

<template>
  <AppLayout title="Create Process">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Process</h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <CreateProcessForm />
      </div>
    </div>
  </AppLayout>
</template>
