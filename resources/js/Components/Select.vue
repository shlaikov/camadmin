<script setup>
import { onMounted, ref } from 'vue'

defineProps({
  modelValue: String,
  options: {
    type: Array,
    default: () => {
      return []
    },
  },
})

defineExpose({ focus: () => input.value.focus() })

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})
</script>

<template>
  <select
    ref="input"
    :value="modelValue"
    class="border-gray-300 focus:border-primary focus:ring focus:ring-secondary focus:ring-opacity-50 rounded-md shadow-sm"
    @input="$emit('update:modelValue', $event.target.value)"
  >
    <option v-for="option in options" :key="option.value" :value="option.value">
      {{ option.text || option.value }}
    </option>
  </select>
</template>
