<template>
  <div ref="container" class="vue-bpmn-modeler-container" @mousemove="updateCoordinates">
    <div ref="canvas" class="canvas"></div>
    <div id="properties-panel-parent" class="properties-panel-parent">
      <div class="properties-deploy-btn">
        <button
          class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
          @click="deployProcess"
        >
          <svg
            class="fill-current w-4 h-4 mr-2"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
          >
            <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
          </svg>
          <span>Deploy</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { BpmnPropertiesPanelModule, BpmnPropertiesProviderModule } from 'bpmn-js-properties-panel'
import minimapModule from 'diagram-js-minimap'

import Modeler from './Modeler'

export default {
  name: 'BpmnModeler',
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
  emits: ['loading', 'shown', 'error'],
  data: function () {
    return {
      modeler: null,
      xmlData: '',
      svgImage: '',
      mousePositions: [],
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

    this.modeler = new Modeler({ canvas, additionalModules })

    if (container) {
      this.modeler.attachTo(container)
    }

    if (!this.diagramXML) {
      this.modeler.createDiagram()

      return
    }

    axios
      .get(route('process.file', { uuid: this.uuid }), {
        headers: { Accept: 'application/xml' },
      })
      .then(({ data }) => {
        this.modeler.importXML(data)
      })
  },
  beforeUnmount: function () {
    this.modeler.destroy()
  },
  methods: {
    fetchDiagram: function (url) {
      const self = this

      alert(url, self)
    },
    updateCoordinates: function (event) {
      const canvas = this.$refs['canvas']
      const rect = canvas.getBoundingClientRect()

      return {
        x: event.clientX - rect.left,
        y: event.clientY - rect.top,
      }
      // console.log(coord.x, coord.y)
    },
    deployProcess: async function () {
      const form = new FormData()

      try {
        const { svg } = await this.modeler.saveSVG()
        form.append('svg', svg)
      } catch (error) {
        console.error(`saveSVG error ${error}`)
      }

      try {
        const { xml } = await this.modeler.saveXML({ format: true })
        form.append('xml', xml)
      } catch (error) {
        console.error(`saveXML error ${error}`)
      }

      axios.post(route('deploy', { uuid: this.uuid }), form, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      })
    },
  },
}
</script>

<style>
@import 'bpmn-js-properties-panel/dist/assets/properties-panel.css';

.canvas {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: 0;
}
.vue-bpmn-modeler-container {
  display: block;
  position: absolute;
  width: 100%;
  height: calc(100vh - 130px);
  top: 0;
  left: 0;
  bottom: 0;
  background: white;
  z-index: 1;
}
.properties-panel-parent {
  position: absolute;
  top: 0;
  bottom: 0;
  right: 0;
  width: 260px;
  z-index: 2;
}
.properties-deploy-btn {
  display: block;
  position: absolute;
  left: 20px;
  bottom: 20px;
  z-index: 2;
}
</style>
