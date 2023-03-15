<script setup>
import { ref } from 'vue'

import AppLayout from '@/Layouts/AppLayout.vue'
import BpmnModeler from '@/Components/DiagramEditors/BPMN/Modeler.vue'
import CmmnModeler from '@/Components/DiagramEditors/CMMN/Modeler.vue'
import DmnModeler from '@/Components/DiagramEditors/DMN/Modeler.vue'
import { definitions } from '@/DTO/defenitions'

const props = defineProps({
  diagram: {
    type: Object,
    default: () => {},
  },
})

const diagram = ref(props.diagram)

if (diagram.value.category) {
  diagram.value = definitions(diagram.value)
}
</script>

<template>
  <AppLayout title="Projects">
    <div class="py-12 relative">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div v-if="diagram.type === 'bpmn'">
            <component
              :is="BpmnModeler"
              :uuid="diagram.uuid"
              :diagram-x-m-l="diagram.url"
              properties-panel
            />
          </div>
          <div v-else-if="diagram.type === 'cmmn'">
            <component
              :is="CmmnModeler"
              :uuid="diagram.uuid"
              :diagram-x-m-l="diagram.url"
              properties-panel
            />
          </div>
          <div v-else-if="diagram.type === 'dmn'">
            <component :is="DmnModeler" :uuid="diagram.uuid" :diagram-x-m-l="diagram.url" />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
