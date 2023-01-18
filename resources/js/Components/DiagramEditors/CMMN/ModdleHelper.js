import { Writer, Reader } from 'moddle-xml'
import { isFunction, isString, assign } from 'min-dash'

export default class ModdleHelper {
  /**
   * Instantiates a CMMN model tree from a given xml string.
   *
   * @param {String}   xmlStr
   * @param {String}   [typeName='bpmn:Definitions'] name of the root element
   * @param {Object}   [options]  options to pass to the underlying reader
   *
   * @returns {Promise<ParseResult, ParseError>}
   */
  static fromXML(model, xmlStr, typeName, options) {
    if (!isString(typeName)) {
      options = typeName
      typeName = 'cmmn:Definitions'
    }

    if (isFunction(options)) {
      options = {}
    }

    const reader = new Reader(assign({ model, lax: true }, options))
    const rootHandler = reader.handler(typeName)

    return reader.fromXML(xmlStr, rootHandler)
  }

  /**
   * Serializes a CMMN 1.1 object tree to XML.
   *
   * @param {String}   element    the root element, typically an instance of `bpmn:Definitions`
   * @param {Object}   [options]  to pass to the underlying writer
   *
   * @returns {Promise<SerializationResult, Error>}
   */
  static toXML(element, options) {
    const writer = new Writer(options)

    return new Promise(function (resolve, reject) {
      try {
        const result = writer.toXML(element)

        return resolve({
          xml: result,
        })
      } catch (err) {
        return reject(err)
      }
    })
  }
}
