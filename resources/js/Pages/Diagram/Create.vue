<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import CreateForm from '@/Pages/Diagram/Partials/CreateForm.vue'
</script>

<template>
  <AppLayout title="Create new diagram">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create new diagram</h2>
    </template>

    <div>
      <div class="mx-auto py-10 sm:px-6 lg:px-8">
        <CreateForm />
      </div>
    </div>
  </AppLayout>
</template>
