<script setup>
import EmptyFile from '@/Components/Icons/EmptyFile.vue'
import { timeSince } from '../helpers'

defineProps({
  file: Object,
})

const onRowClick = (row) => {
  if (!row.uuid) {
    const { pathname } = window.location

    return (window.location = `${pathname}/process-definition/${row.id}/diagram`)
  }

  window.location = `/diagram/${row.uuid}`
}
</script>

<template>
  <div
    class="w-full p-4 bg-white border border-grey-300 rounded-md cursor-pointer hover:shadow-md transition"
    :title="file.name"
    @click="() => onRowClick(file)"
  >
    <img
      v-if="file.preview"
      class="mb-4 mx-auto md:h-64 md:w-64 h-40 w-40"
      :alt="file.name"
      :src="`/diagram/${file.uuid}/preview.svg`"
    />
    <div v-else class="mb-4 mx-auto md:h-64 md:w-64 h-40 w-40">
      <EmptyFile class="mx-auto md:h-64 md:w-16 h-20 w-20 opacity-25" />
    </div>

    <h4 class="mb-2 text-lg font-semibold truncate">{{ file.name }}</h4>
    <p class="flex font-semibold text-sm text-gray-600 truncate">
      <span
        v-if="file.type"
        class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
      >
        {{ file.type }}
      </span>
      <span
        v-if="file.updated_at"
        class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          aria-hidden="true"
          class="mr-1 w-3 h-3"
          viewBox="0 0 20 20"
        >
          <path
            fill-rule="evenodd"
            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
            clip-rule="evenodd"
          ></path>
        </svg>
        {{ timeSince(file.updated_at) + ' ago' }}
      </span>
      <span
        v-if="file.runningInstances"
        class="bg-success text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
      >
        {{ file.runningInstances }} in process
      </span>
      <span
        v-if="file.failedJobs"
        class="bg-error text-white text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
      >
        {{ file.failedJobs }} failed
      </span>
    </p>
  </div>
</template>
