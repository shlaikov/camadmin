import { usePage } from '@inertiajs/vue3'
import { defineStore } from 'pinia'

export const useAppStore = defineStore('app', {
  state: () => {
    const { name, app_version, has_keycloak } = usePage().props

    return {
      name,
      version: app_version,
      hasKeycloak: has_keycloak,
      isAboutModalOpen: false,
    }
  },
  getters: {},
})
