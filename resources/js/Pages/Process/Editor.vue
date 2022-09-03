<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
// import BpmnViewer from '@/Components/BPMN/BpmnViewer.vue'
import BpmnModeler from '@/Components/BPMN/BpmnModeler.vue'
</script>

<template>
  <AppLayout title="Projects">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
    </template>

    <div class="py-12 relative">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <BpmnModeler url="/example.bpmn" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
