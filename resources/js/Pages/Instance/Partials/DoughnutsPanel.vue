<script setup>
import { Chart, ArcElement, Tooltip, Title } from 'chart.js'
import { Doughnut } from 'vue-chartjs'

Chart.register(ArcElement, Tooltip, Title)

const props = defineProps({
  instance: {
    required: true,
    type: Object,
  },
})

const doughnutColors = [
  '#6b21a8',
  '#6c5ce7',
  '#9333ea',
  '#6366f1',
  '#4338ca',
  '#a855f7',
  '#6d28d9',
  '#9333ea',
]

const plugin = {
  id: 'emptyDoughnut',
  afterDraw(chart, _args, options) {
    const { datasets } = chart.data
    const { color, width, radiusDecrease } = options

    let hasData = false

    for (let i = 0; i < datasets.length; i += 1) {
      const dataset = datasets[i]

      hasData |= dataset.data.reduce((partialSum, a) => partialSum + a, 0) > 0
    }

    if (!hasData) {
      const {
        chartArea: { left, top, right, bottom },
        ctx,
      } = chart
      const centerX = (left + right) / 2
      const centerY = (top + bottom) / 2
      const r = Math.min(right - left, bottom - top) / 2

      ctx.beginPath()
      ctx.lineWidth = width || 2
      ctx.strokeStyle = color || '#e5e5e5'
      ctx.arc(centerX, centerY, r - radiusDecrease || 0, 0, 2 * Math.PI)
      ctx.stroke()
    }
  },
}

const labels = props.instance.statistics.map((i) => i.name)

const options = (title) => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    title: {
      display: true,
      text: title,
    },
    emptyDoughnut: {
      color: '#e5e5e5',
      width: 70,
      radiusDecrease: 40,
    },
  },
})
</script>

<template>
  <div class="flex justify-between">
    <Doughnut
      :data="{
        labels,
        datasets: [
          {
            backgroundColor: doughnutColors,
            data: instance.statistics.map((i) => i.instances),
          },
        ],
      }"
      :options="options('Running Process Instances')"
    />
    <Doughnut
      :data="{
        labels,
        datasets: [
          {
            backgroundColor: doughnutColors,
            data: instance.statistics.map((i) => i.incidents),
          },
        ],
      }"
      :options="options('Open Incidents')"
      :plugins="[plugin]"
    />
    <Doughnut
      :data="{
        labels,
        datasets: [
          {
            backgroundColor: doughnutColors,
            data: [40, 20],
          },
        ],
      }"
      :options="options('Open Human Tasks')"
      :plugins="[plugin]"
    />
  </div>
</template>
