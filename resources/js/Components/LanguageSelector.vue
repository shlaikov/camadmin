<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

import JetDropdown from '@/Components/Dropdown.vue'
import JetDropdownLink from '@/Components/DropdownLink.vue'

const locale = computed(() => usePage().props.locale)
</script>

<template>
  <div>
    <JetDropdown align="bottom-right" width="80">
      <template #trigger>
        <button
          class="flex items-center gap-x-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300"
          data-dropdown-position="dropup"
        >
          {{ locale }}
          <svg
            class="h-5 w-5 flex-none text-gray-400"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
          >
            <path
              fill-rule="evenodd"
              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
              clip-rule="evenodd"
            />
          </svg>
        </button>
      </template>

      <template #content>
        <div class="w-20">
          <template v-if="$page.props.locale">
            <JetDropdownLink
              v-for="lang in ['en', 'fr']"
              :key="lang"
              :href="route('language', lang)"
            >
              {{ lang.toUpperCase() }}
            </JetDropdownLink>
          </template>
        </div>
      </template>
    </JetDropdown>
  </div>
</template>
