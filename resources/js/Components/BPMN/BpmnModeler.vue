<template>
  <div ref="container" class="vue-bpmn-modeler-container">
    <div ref="canvas" class="canvas"></div>
    <div id="properties-panel-parent" class="properties-panel-parent"></div>
  </div>
</template>

<script>
import minimapModule from 'diagram-js-minimap'
import camundaModdleDescriptor from 'camunda-bpmn-moddle/resources/camunda.json'
import { BpmnPropertiesPanelModule, BpmnPropertiesProviderModule } from 'bpmn-js-properties-panel'

import Modeler from './Modeler'

export default {
  name: 'BpmnModeler',
  props: {
    diagramXML: String,
    propertiesPanel: null,
  },
  emits: ['loading', 'shown', 'error'],
  data: function () {
    return {
      modeler: {},
      xmlData: '',
      svgImage: '',
      isSvg: false,
    }
  },
  watch: {
    diagramXML(val) {
      this.openDiagram(val)
    },
  },
  mounted: function () {
    const container = this.$refs.container

    let canvas = this.$refs['canvas']
    let additionalModules = [minimapModule]

    if (this.propertiesPanel === '' || this.propertiesPanel) {
      additionalModules = additionalModules.concat([
        BpmnPropertiesPanelModule,
        BpmnPropertiesProviderModule,
      ])
    }

    this.modeler = new Modeler({
      container: canvas,
      additionalModules: additionalModules,
      keyboard: {
        bindTo: document,
      },
      moddleExtensions: {
        camunda: camundaModdleDescriptor,
      },
      propertiesPanel: {
        parent: '#properties-panel-parent',
      },
    })

    if (container) {
      this.modeler.attachTo(container)
    }

    this.modeler.createDiagram()
  },
  beforeUnmount: function () {
    this.modeler.destroy()
  },
  methods: {
    fetchDiagram: function (url) {
      const self = this

      alert(url, self)
    },
  },
}
</script>

<style>
.vue-bpmn-modeler-container {
  display: block;
  position: absolute;
  width: 100%;
  height: calc(100vh - 130px);
  top: 0;
  left: 0;
  bottom: 0;
  background: white;
}
</style>
