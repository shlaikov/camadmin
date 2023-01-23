import BpmnModeler from 'bpmn-js/lib/Modeler'

import CmmnModeler from './CMMN/Modeler'
import DmnModeler from './DMN/Modeler'

export default class DiagramModeler {
  constructor(type, options) {
    const modelerOptions = this._getOptions(options)

    switch (type) {
      case 'bpmn':
        return new BpmnModeler(modelerOptions)
      case 'cmmn':
        return new CmmnModeler(modelerOptions)
      case 'dmn':
        return new DmnModeler(modelerOptions)
      default:
        throw new Error('Missing type of diagram')
    }
  }

  get baseOptions() {
    return {
      keyboard: {
        bindTo: document,
      },
      propertiesPanel: {
        parent: `#${this.propertiesPanelClass}`,
      },
    }
  }

  get propertiesPanelClass() {
    return 'properties-panel-parent'
  }

  _getOptions(options) {
    return {
      ...this.baseOptions,
      ...options,
    }
  }
}
