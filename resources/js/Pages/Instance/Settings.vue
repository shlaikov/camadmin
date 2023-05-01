<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import JetSectionBorder from '@/Components/SectionBorder.vue'
import Navbar from './Partials/Navbar.vue'
import UpdateInformationForm from './Partials/UpdateInformationForm.vue'
import DeleteForm from './Partials/DeleteForm.vue'

defineProps({
  instanceId: {
    required: true,
    type: String,
  },
})
</script>

<template>
  <AppLayout title="Settings">
    <template #header>
      <Navbar :instance-id="instanceId" />
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <UpdateInformationForm :instance-id="instanceId" />
        <JetSectionBorder />
        <DeleteForm :instance-id="instanceId" />
      </div>
    </div>
  </AppLayout>
</template>
