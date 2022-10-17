<script setup>
import EmptyFile from '@/Components/Icons/EmptyFile.vue'

defineProps({
  process: Object,
})

const onRowClick = (row) => {
  window.location = `/process/${row.uuid}`
}
</script>

<template>
  <div
    class="p-8 border border-grey-300 cursor-pointer hover:shadow-md"
    @click="() => onRowClick(process)"
  >
    <img
      v-if="process.preview"
      class="mb-4 mx-auto md:h-64 md:w-64 h-40 w-40"
      :alt="process.name"
      :src="`/process/${process.uuid}/preview.svg`"
    />
    <EmptyFile v-else class="mb-4 mx-auto md:h-64 md:w-32 h-20 w-20 opacity-25" />

    <h4 class="mb-2 text-lg font-semibold truncate">{{ process.name }}</h4>
  </div>
</template>
