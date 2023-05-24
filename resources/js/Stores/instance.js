import { usePage } from '@inertiajs/vue3'
import { defineStore } from 'pinia'

import camundaApi from '@/Api/Camunda'
import { mergeDefenitions } from '@/DTO/defenitions'
import * as Utils from '../Utils/charts'

export const useInstanceStore = defineStore('instance', {
  state: () => {
    return {
      /** @type {{ name: string, id: number, url: string }[]} */
      instances: usePage().props.instances,
      mounted: false,
    }
  },
  getters: {
    getInstanceById: (state) => {
      return (instanceId) => state.instances.find((instance) => instance.id === instanceId)
    },
    instanceStatistics: (state) => {
      let runningInstances = 0,
        incidents = 0,
        failedJobs = 0

      state.instances.forEach((instance) => {
        if (instance.error || !instance.statistics) {
          return
        }

        instance.statistics.forEach((i) => {
          runningInstances += i.instances
          incidents += i.incidents
          failedJobs += i.failedJobs
        })
      })

      return { runningInstances, failedJobs, incidents }
    },
    instanceActivity: (state) => {
      const datasets = []

      const labels = Utils.getLastMonths({
        count: 6,
        // section: 3,
      })

      state.instances.forEach((instance, index) => {
        if (instance.error || !instance.logs) {
          return
        }

        const logs = instance.logs.map((i) => {
          const date = new Date(i.startTime)
          const year = date.getFullYear()

          return {
            x: `${Utils.MONTHS[date.getMonth()]} ${String(year).slice(-2)}`,
            y: 1,
          }
        })

        const filled = logs.reduce((m, { x, y }) => m.set(x, (m.get(x) || 0) + y), new Map())
        const data = labels.map((i) => filled.get(i) || 0)

        datasets.push({
          label: instance.name,
          data: data,
          borderSkipped: true,
          backgroundColor: Utils.colorize(true, index),
        })
      })

      return { labels, datasets }
    },
  },
  actions: {
    async getVersion(instanceId) {
      const instance = this.getInstanceById(instanceId)

      try {
        const { data } = await camundaApi().get(`${instance.id}/version`)

        return data.version
      } catch (error) {
        instance.error = true

        console.error(`Connection error with "${instance.url}" service`)

        return false
      }
    },
    async fetchDefenitions(instanceId) {
      const instance = this.getInstanceById(instanceId)

      try {
        const version = await this.getVersion(instanceId)

        if (instance.error || !version) {
          return
        }

        camundaApi()
          .get(`${instance.id}/process-definition/statistics`, {
            params: {
              incidents: true,
            },
          })
          .then(({ data }) => {
            instance.version = version
            instance.statistics = mergeDefenitions(data)
          })
      } catch (error) {
        instance.error = true

        return error
      }
    },
    async fetchTaskStatistics(instanceId) {
      const unfinished = await camundaApi().get(`${instanceId}/history/task/count`, {
        params: {
          unfinished: true,
        },
      })

      const assigned = await camundaApi().get(`${instanceId}/history/task/count`, {
        params: {
          unfinished: true,
          assigned: true,
        },
      })

      const withCandidateGroups = await camundaApi().get(`${instanceId}/history/task/count`, {
        params: {
          unfinished: true,
          unassigned: true,
          withCandidateGroups: true,
        },
      })

      const withoutCandidateGroups = await camundaApi().get(`${instanceId}/history/task/count`, {
        params: {
          unfinished: true,
          unassigned: true,
          withoutCandidateGroups: true,
        },
      })

      const instance = this.getInstanceById(instanceId)

      instance.taskStaticstics = {
        unfinished: unfinished.data,
        assigned: assigned.data,
        withCandidateGroups: withCandidateGroups.data,
        withoutCandidateGroups: withoutCandidateGroups.data,
      }
    },
    async fetchInstanceActivity(instanceId) {
      const instance = this.getInstanceById(instanceId)

      camundaApi()
        .get(`${instanceId}/history/activity-instance`, {
          params: {
            sortBy: 'startTime',
            sortOrder: 'desc',
          },
        })
        .then(({ data }) => {
          instance.logs = data
        })
        .catch(() => {
          instance.logs = []
        })
    },
  },
})
