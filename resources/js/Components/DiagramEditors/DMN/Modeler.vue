<script setup>
import Modeler from '../Modeler.vue'

import 'dmn-js/dist/assets/diagram-js.css'
import 'dmn-js/dist/assets/dmn-js-shared.css'
import 'dmn-js/dist/assets/dmn-js-drd.css'
import 'dmn-js/dist/assets/dmn-js-decision-table.css'
import 'dmn-js/dist/assets/dmn-js-decision-table-controls.css'
import 'dmn-js/dist/assets/dmn-font/css/dmn.css'

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
    type="dmn"
    :uuid="uuid"
    :diagram-x-m-l="diagramXML"
    properties-panel
    :additional-modules="[]"
  />
</template>

<style>
.dmn-decision-table-container .tjs-container {
  margin-left: 20px;
}
.djs-container.with-palette .dmn-definitions {
  min-width: 300px;
}
</style>
