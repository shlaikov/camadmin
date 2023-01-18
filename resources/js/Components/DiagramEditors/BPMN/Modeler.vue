<template>
  <modeler
    type="bpmn"
    :uuid="uuid"
    :diagram-x-m-l="diagramXML"
    properties-panel
    :additional-modules="additionalModules"
    :options="options"
  />
</template>

<script>
import { BpmnPropertiesPanelModule, BpmnPropertiesProviderModule } from 'bpmn-js-properties-panel'
import CamundaModdleDescriptor from 'camunda-bpmn-moddle/resources/camunda.json'
import minimapModule from 'diagram-js-minimap'

import 'bpmn-js/dist/assets/diagram-js.css'
import 'bpmn-js/dist/assets/bpmn-font/css/bpmn.css'

import Modeler from '../Modeler.vue'

export default {
  name: 'BpmnModeler',
  components: {
    Modeler,
  },
  props: {
    uuid: {
      type: String,
      required: true,
    },
    diagramXML: {
      type: String,
      required: false,
    },
    propertiesPanel: Boolean,
  },
  data: function () {
    return {
      options: {
        moddleExtensions: {
          camunda: CamundaModdleDescriptor,
        },
      },
      additionalModules: [],
    }
  },
  mounted: function () {
    let additionalModules = [minimapModule]

    if (this.propertiesPanel) {
      additionalModules = additionalModules.concat([
        BpmnPropertiesPanelModule,
        BpmnPropertiesProviderModule,
      ])
    }

    this.additionalModules = additionalModules
  },
}
</script>

<style>
@import 'bpmn-js-properties-panel/dist/assets/properties-panel.css';
</style>
