import Modeler from 'bpmn-js/lib/Modeler'

import 'bpmn-js/dist/assets/diagram-js.css'
import 'bpmn-js/dist/assets/bpmn-font/css/bpmn.css'

export default class CustomModeler extends Modeler {
  constructor(options) {
    super(options)
  }
}
