import Modeler from 'bpmn-js/lib/Modeler'
import camundaModdleDescriptor from 'camunda-bpmn-moddle/resources/camunda.json'

import 'bpmn-js/dist/assets/diagram-js.css'
import 'bpmn-js/dist/assets/bpmn-font/css/bpmn.css'

export default class CustomModeler extends Modeler {
  constructor(options) {
    options = {
      ...options,
      container: options.canvas,
      additionalModules: options.additionalModules,
      keyboard: {
        bindTo: document,
      },
      moddleExtensions: {
        camunda: camundaModdleDescriptor,
      },
      propertiesPanel: {
        parent: '#properties-panel-parent',
      },
    }

    super(options)
  }

  get propertiesPanelClass() {
    return 'properties-panel-parent'
  }
}
