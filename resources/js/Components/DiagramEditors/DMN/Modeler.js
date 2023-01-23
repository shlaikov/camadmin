import DmnModeler from 'dmn-js/lib/Modeler'
import { wrapForCompatibility } from 'bpmn-js/lib/util/CompatibilityUtil'
import { query as domQuery } from 'min-dom'
import { innerSVG } from 'tiny-svg'

import InitialDiagram from './InitialDiagram'

export default class CustomModeler extends DmnModeler {
  constructor(options) {
    super(options)
  }

  get initialDiagram() {
    return InitialDiagram
  }

  createDiagram = wrapForCompatibility(() => this.importXML(this.initialDiagram))

  saveSVG = wrapForCompatibility(() => {
    const self = this

    return new Promise(function (resolve, reject) {
      self._emit('saveSVG.start')

      let svg, err

      try {
        const activeEditor = self.getActiveViewer()
        const canvas = activeEditor.get('canvas')

        const contentNode = canvas._viewport,
          defsNode = domQuery('defs', canvas._svg)

        const contents = innerSVG(contentNode),
          defs = (defsNode && defsNode.outerHTML) || '',
          bbox = contentNode.getBBox()

        svg =
          '<?xml version="1.0" encoding="utf-8"?>\n' +
          '<!-- created with dmn-js / http://bpmn.io -->\n' +
          '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">\n' +
          '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ' +
          'width="' +
          bbox.width +
          '" height="' +
          bbox.height +
          '" ' +
          'viewBox="' +
          bbox.x +
          ' ' +
          bbox.y +
          ' ' +
          bbox.width +
          ' ' +
          bbox.height +
          '" version="1.1">' +
          defs +
          contents +
          '</svg>'
      } catch (e) {
        err = e
      }

      self._emit('saveSVG.done', {
        error: err,
        svg: svg,
      })

      if (!err) {
        return resolve({ svg: svg })
      }

      return reject(err)
    })
  })
}
