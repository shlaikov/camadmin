<template>
  <div ref="container" class="vue-bpmn-diagram-container"></div>
</template>

<script>
import BpmnJS from 'bpmn-js'

export default {
  name: 'BpmnViewer',
  props: {
    url: {
      type: String,
      required: true,
    },
    options: {
      type: Object,
    },
  },
  emits: ['loading', 'shown', 'error'],
  data: function () {
    return {
      diagramXML: null,
    }
  },
  watch: {
    url: function (val) {
      this.$emit('loading')
      this.fetchDiagram(val)
    },
    diagramXML: function (val) {
      this.bpmnViewer.importXML(val)
    },
  },
  mounted: function () {
    const container = this.$refs.container
    const self = this
    const _options = Object.assign(
      {
        container: container,
      },
      this.options
    )

    this.bpmnViewer = new BpmnJS(_options)
    this.bpmnViewer.on('import.done', function (event) {
      const error = event.error
      const warnings = event.warnings

      if (error) {
        self.$emit('error', error)
      } else {
        self.$emit('shown', warnings)
      }

      self.bpmnViewer.get('canvas').zoom('fit-viewport')
    })

    if (this.url) {
      this.fetchDiagram(this.url)
    }
  },
  beforeUnmount: function () {
    this.bpmnViewer.destroy()
  },
  methods: {
    fetchDiagram: function (url) {
      const self = this

      axios
        .get(url)
        .then(function (response) {
          self.diagramXML = response.data
        })
        .catch(function (err) {
          self.$emit('error', err)
        })
    },
  },
}
</script>

<style>
.vue-bpmn-diagram-container {
  display: block;
  height: 100%;
  width: 100%;
}
</style>
