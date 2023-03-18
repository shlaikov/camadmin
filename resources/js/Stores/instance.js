import { usePage } from '@inertiajs/vue3'
import { defineStore } from 'pinia'

import camundaApi from '@/Api/Camunda'
import { mergeDefenitions } from '@/DTO/defenitions'

export const useInstanceStore = defineStore('instance', {
  state: () => {
    return {
      /** @type {{ name: string, id: number, url: string }[]} */
      instances: usePage().props.instances,
    }
  },
  getters: {
    getInstanceById: (state) => {
      return (instanceId) => state.instances.find((instance) => instance.id === instanceId)
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

        return error
      }
    },
    async fetchDefenitions(instanceId) {
      const instance = this.getInstanceById(instanceId)

      if (instance.error) {
        return
      }

      try {
        const version = await this.getVersion(instanceId)

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
