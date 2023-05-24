import colorLib from '@kurkle/color'

import { valueOrDefault } from '../helpers'

let _seed = Date.now()

export function srand(seed) {
  _seed = seed
}

export function rand(min, max) {
  min = valueOrDefault(min, 0)
  max = valueOrDefault(max, 0)

  _seed = (_seed * 9301 + 49297) % 233280

  return min + (_seed / 233280) * (max - min)
}

export function numbers(config) {
  const cfg = config || {}

  const min = valueOrDefault(cfg.min, 0)
  const max = valueOrDefault(cfg.max, 100)
  const from = valueOrDefault(cfg.from, [])
  const count = valueOrDefault(cfg.count, 8)
  const decimals = valueOrDefault(cfg.decimals, 8)
  const continuity = valueOrDefault(cfg.continuity, 1)
  const dfactor = Math.pow(10, decimals) || 0
  const data = []

  let i, value

  for (i = 0; i < count; ++i) {
    value = (from[i] || 0) + this.rand(min, max)

    if (this.rand() <= continuity) {
      data.push(Math.round(dfactor * value) / dfactor)
    } else {
      data.push(null)
    }
  }

  return data
}

export const MONTHS = [
  'January',
  'February',
  'March',
  'April',
  'May',
  'June',
  'July',
  'August',
  'September',
  'October',
  'November',
  'December',
]

export function months(config) {
  const cfg = config || {}
  const count = cfg.count || 12
  const values = []

  let i, value

  for (i = 0; i < count; ++i) {
    value = MONTHS[Math.ceil(i) % 12]
    values.push(value.substring(0, cfg.section))
  }

  return values
}

export function getLastMonths(config) {
  const cfg = config || {}
  const count = cfg.count || 12
  const values = []

  const today = new Date()

  let i, d, month, year

  for (i = 0; i < count; ++i) {
    d = new Date(today.getFullYear(), today.getMonth() - i, 1)
    month = MONTHS[d.getMonth()]
    year = d.getFullYear()

    values.push(`${month.substring(0, cfg.section)} ${String(year).slice(-2)}`)
  }

  return values.reverse()
}

export const COLORS = [
  '#a5b4fc',
  '#c7d2fe',
  '#eef2ff',
  '#ede9fe',
  '#fdf4ff',
  '#dbeafe',
  '#ddd6fe',
  '#f3e8ff',
  '#fdf4ff',
  '#faf5ff',
  '#dbeafe',
  '#c4b5fd',
]

export function color(index) {
  return COLORS[index % COLORS.length]
}

export function transparentize(value, opacity) {
  const alpha = opacity === undefined ? 0.5 : 1 - opacity

  return colorLib(value).alpha(alpha).rgbString()
}

export function colorize(opaque, index = 0, instensive = 150) {
  return ({ parsed }) => {
    if (!parsed) {
      return
    }

    const colorByIndex = color(index)

    return opaque ? colorByIndex : transparentize(colorByIndex, 1 - Math.abs(parsed.y / instensive))
  }
}
