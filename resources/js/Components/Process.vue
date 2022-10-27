<script setup>
import EmptyFile from '@/Components/Icons/EmptyFile.vue'
import { timeSince } from '../helpers'

defineProps({
  process: Object,
})

const onRowClick = (row) => {
  window.location = `/process/${row.uuid}`
}
</script>

<template>
  <div
    class="p-4 border border-grey-300 rounded cursor-pointer hover:shadow-md"
    @click="() => onRowClick(process)"
  >
    <img
      v-if="process.preview"
      class="mb-4 mx-auto md:h-64 md:w-64 h-40 w-40"
      :alt="process.name"
      :src="`/process/${process.uuid}/preview.svg`"
    />
    <EmptyFile v-else class="mb-4 mx-auto md:h-64 md:w-16 h-20 w-20 opacity-25" />

    <h4 class="mb-2 text-lg font-semibold truncate">{{ process.name }}</h4>
    <p class="font-semibold text-sm text-gray-600 truncate">
      <span
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
        {{ timeSince(process.updated_at) + ' ago' }}
      </span>
    </p>
  </div>
</template>
