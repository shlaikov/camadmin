import { usePage } from '@inertiajs/vue3'
import { defineStore } from 'pinia'

import camundaApi from '@/Api/Camunda'
import { mergeDefenitions } from '@/DTO/defenitions'

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
  },
})
