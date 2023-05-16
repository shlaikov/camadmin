<template>
  <div ref="container" class="vue-modeler-container" @mousemove="updateCoordinates">
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
import DiagramModeler from './DiagramModeler'

export default {
  name: 'DiagramModeler',
  props: {
    type: {
      type: String,
      required: true,
    },
    options: Object,
    uuid: {
      type: String,
      required: true,
    },
    diagramXML: {
      type: String,
      required: false,
    },
    additionalModules: Array,
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

    this.modeler = new DiagramModeler(this.type, {
      ...this.options,
      container: this.$refs['canvas'],
      additionalModules: this.additionalModules,
    })

    if (container) {
      this.modeler.attachTo(container)
    }

    if (!this.diagramXML) {
      this.modeler.createDiagram()

      return
    }

    axios
      .get(route('diagrams.file', { uuid: this.uuid, extension: this.type }), {
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
    updateCoordinates: function (event) {
      const canvas = this.$refs['canvas']
      const rect = canvas.getBoundingClientRect()

      return {
        x: event.clientX - rect.left,
        y: event.clientY - rect.top,
      }
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
.vue-modeler-container {
  display: block;
  position: absolute;
  width: 100%;
  height: calc(100vh - 100px);
  top: 0;
  left: 0;
  bottom: 0;
  background: white;
  z-index: 1;
}
.canvas {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: 0;
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
