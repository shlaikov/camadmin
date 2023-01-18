import CmmnModeler from 'cmmn-js/lib/Modeler'
import { wrapForCompatibility } from 'bpmn-js/lib/util/CompatibilityUtil'
import { query as domQuery } from 'min-dom'
import { innerSVG } from 'tiny-svg'
import ModdleHelper from './ModdleHelper'
import { importCmmnDiagram } from 'cmmn-js/lib/import/Importer'

export default class CustomModeler extends CmmnModeler {
  constructor(options) {
    super(options)
  }

  get initialDiagram() {
    return (
      '<?xml version="1.0" encoding="UTF-8"?>' +
      '<cmmn:definitions xmlns:dc="http://www.omg.org/spec/CMMN/20151109/DC" ' +
      'xmlns:di="http://www.omg.org/spec/CMMN/20151109/DI" ' +
      'xmlns:cmmndi="http://www.omg.org/spec/CMMN/20151109/CMMNDI" ' +
      'xmlns:cmmn="http://www.omg.org/spec/CMMN/20151109/MODEL" ' +
      'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" id="Test" ' +
      'targetNamespace="http://bpmn.io/schema/cmmn">' +
      '<cmmn:case id="Case_1">' +
      '<cmmn:casePlanModel id="CasePlanModel_1" name="A CasePlanModel">' +
      '<cmmn:planItem id="PlanItem_1" definitionRef="Task_1" />' +
      '<cmmn:task id="Task_1" />' +
      '</cmmn:casePlanModel>' +
      '</cmmn:case>' +
      '<cmmndi:CMMNDI>' +
      '<cmmndi:CMMNDiagram id="CMMNDiagram_1">' +
      '<cmmndi:Size width="500" height="500" />' +
      '<cmmndi:CMMNShape id="DI_CasePlanModel_1" cmmnElementRef="CasePlanModel_1">' +
      '<dc:Bounds x="114" y="63" width="534" height="389" />' +
      '<cmmndi:CMMNLabel />' +
      '</cmmndi:CMMNShape>' +
      '<cmmndi:CMMNShape id="PlanItem_1_di" cmmnElementRef="PlanItem_1">' +
      '<dc:Bounds x="150" y="96" width="100" height="80" />' +
      '<cmmndi:CMMNLabel />' +
      '</cmmndi:CMMNShape>' +
      '</cmmndi:CMMNDiagram>' +
      '</cmmndi:CMMNDI>' +
      '</cmmn:definitions>'
    )
  }

  createDiagram = wrapForCompatibility(() => this.importXML(this.initialDiagram))

  importDefinitions = wrapForCompatibility((definitions) => {
    const self = this

    return new Promise(function (resolve, reject) {
      try {
        if (self._definitions) {
          self.clear()
        }

        self._definitions = definitions

        importCmmnDiagram(self, definitions, (error, warnings) => {
          resolve({ error, warnings })
        })
      } catch (e) {
        reject(e)
      }
    })
  })

  saveSVG = wrapForCompatibility(() => {
    const self = this

    return new Promise(function (resolve, reject) {
      self._emit('saveSVG.start')

      let svg, err

      try {
        const canvas = self.get('canvas')

        const contentNode = canvas.getDefaultLayer(),
          defsNode = domQuery('defs', canvas._svg)

        const contents = innerSVG(contentNode),
          defs = (defsNode && defsNode.outerHTML) || ''

        const bbox = contentNode.getBBox()

        svg =
          '<?xml version="1.0" encoding="utf-8"?>\n' +
          '<!-- created with cmmn-js / http://bpmn.io -->\n' +
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

  saveXML = wrapForCompatibility((options = {}) => {
    const self = this

    let definitions = this._definitions

    return new Promise(function (resolve) {
      if (!definitions) {
        return resolve({
          error: new Error('no definitions loaded'),
        })
      }

      definitions =
        self._emit('saveXML.start', {
          definitions: definitions,
        }) || definitions

      self._toXML(definitions, options).then(function (result) {
        let xml = result.xml

        xml =
          self._emit('saveXML.serialized', {
            xml: xml,
          }) || xml

        return resolve({
          xml: xml,
        })
      })
    })
      .catch(function (error) {
        return { error: error }
      })
      .then(function (result) {
        self._emit('saveXML.done', result)

        const error = result.error

        if (error) {
          return Promise.reject(error)
        }

        return result
      })
  })

  importXML = wrapForCompatibility((xml, cmmnDiagram) => {
    const self = this

    return new Promise(function (resolve, reject) {
      xml = self._emit('import.parse.start', { xml: xml }) || xml

      self
        ._fromXML(xml, 'cmmn:Definitions')
        .then(function (result) {
          let definitions = result.rootElement

          definitions =
            self._emit('import.parse.complete', {
              error: result.err,
              definitions,
              context: result,
            }) || definitions

          const parseWarnings = result.warnings

          self
            .importDefinitions(definitions, cmmnDiagram)
            .then(function (result) {
              const allWarnings = [].concat(parseWarnings, result.warnings || [])
              self._emit('import.done', { error: null, warnings: allWarnings })

              return resolve({ warnings: allWarnings })
            })
            .catch(function (err) {
              const allWarnings = [].concat(parseWarnings, err.warnings || [])
              self._emit('import.done', { error: err, warnings: allWarnings })

              return reject(self._addWarningsToError(err, allWarnings))
            })
        })
        .catch(function (err) {
          self._emit('import.parse.complete', {
            error: err,
          })

          err = self._checkValidationError(err)
          self._emit('import.done', { error: err, warnings: err.warnings })

          return reject(err)
        })
    })
  })

  async _toXML(element, options) {
    return await ModdleHelper.toXML(element, options)
  }

  async _fromXML(xml, typeName, options = {}) {
    return await ModdleHelper.fromXML(this._moddle, xml, typeName, options)
  }

  _checkValidationError(err) {
    const pattern = /unparsable content <([^>]+)> detected([\s\S]*)$/
    const match = pattern.exec(err.message)

    if (match) {
      err.message =
        'unparsable content <' +
        match[1] +
        '> detected; ' +
        'this may indicate an invalid CMMN 1.1 diagram file' +
        match[2]
    }

    return err
  }

  _addWarningsToError(err, warningsAry) {
    err.warnings = warningsAry

    return err
  }
}
