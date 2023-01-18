<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import BpmnModeler from '@/Components/DiagramEditors/BPMN/Modeler.vue'
import CmmnModeler from '@/Components/DiagramEditors/CMMN/Modeler.vue'
</script>

<template>
  <AppLayout title="Projects">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
    </template>

    <div class="py-12 relative">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div v-if="$page.props.diagram.type === 'bpmn'">
            <component
              :is="BpmnModeler"
              :uuid="$page.props.diagram.uuid"
              :diagram-x-m-l="$page.props.diagram.url"
              properties-panel
            />
          </div>
          <div v-else-if="$page.props.diagram.type === 'cmmn'">
            <component
              :is="CmmnModeler"
              :uuid="$page.props.diagram.uuid"
              :diagram-x-m-l="$page.props.diagram.url"
              properties-panel
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
