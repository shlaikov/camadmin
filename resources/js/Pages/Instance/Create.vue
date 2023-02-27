<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import CreateForm from '@/Pages/Instance/Partials/CreateForm.vue'
</script>

<template>
  <AppLayout title="Add new instance">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add new instance</h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <CreateForm />
      </div>
    </div>
  </AppLayout>
</template>
