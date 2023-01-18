<script setup>
import PropertiesPanelModule from 'cmmn-js-properties-panel'
import PropertiesProviderModule from 'cmmn-js-properties-panel/lib/provider/camunda'
import CamundaModdleDescriptor from 'camunda-cmmn-moddle/resources/camunda'

import Modeler from '../Modeler.vue'

import 'cmmn-js/dist/assets/diagram-js.css'
import 'cmmn-js/dist/assets/cmmn-font/css/cmmn-embedded.css'

defineProps({
  uuid: {
    type: String,
    required: true,
  },
  diagramXML: {
    type: String,
    required: false,
  },
  propertiesPanel: Boolean,
})
</script>

<template>
  <modeler
    type="cmmn"
    :uuid="uuid"
    :diagram-x-m-l="diagramXML"
    properties-panel
    :additional-modules="[PropertiesPanelModule, PropertiesProviderModule]"
    :options="{
      moddleExtensions: {
        camunda: CamundaModdleDescriptor,
      },
    }"
  />
</template>

<style>
@import 'cmmn-js-properties-panel/dist/assets/cmmn-js-properties-panel.css';
</style>
