import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export default {
  methods: {
    /**
     * Translate the given key.
     */
    __(key, replace = {}) {
      const language = computed(() => usePage().props.language)

      let translation = language.value[key] ? language.value[key] : key

      Object.keys(replace).forEach(function (key) {
        translation = translation.replace(':' + key, replace[key])
      })

      return translation
    },
  },
}
