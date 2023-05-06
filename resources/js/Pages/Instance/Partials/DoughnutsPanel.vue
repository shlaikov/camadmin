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

let labels = []

const doughnutColors = [
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
      ctx.strokeStyle = color || '#f9fafb'
      ctx.arc(centerX, centerY, r - radiusDecrease || 0, 0, 2 * Math.PI)
      ctx.stroke()
    }
  },
}

if (props.instance.statistics) {
  labels = props.instance.statistics.map((i) => i.name)
}

const options = (title) => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    title: {
      display: true,
      text: title,
    },
    legend: {
      display: false,
    },
    emptyDoughnut: {
      width: 70,
      radiusDecrease: 40,
    },
  },
})
</script>

<template>
  <div v-if="instance.statistics && instance.taskStaticstics" class="flex justify-between">
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
        labels: ['Assigned to a user', 'Assigned to 1 or more groups', 'Unassigned'],
        datasets: [
          {
            backgroundColor: [].concat(doughnutColors).reverse(),
            data: [
              instance.taskStaticstics.assigned,
              instance.taskStaticstics.withCandidateGroups,
              instance.taskStaticstics.withoutCandidateGroups,
            ],
          },
        ],
      }"
      :options="options('Open Human Tasks')"
      :plugins="[plugin]"
    />
  </div>
</template>
