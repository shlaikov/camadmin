<script setup>
import { onMounted, ref } from 'vue'

defineProps({
  modelValue: String,
  placeholder: String,
})

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
  <textarea
    ref="input"
    rows="4"
    class="border-gray-300 focus:border-primary focus:ring focus:ring-secondary focus:ring-opacity-50 rounded-md shadow-sm"
    :value="modelValue"
    :placeholder="placeholder"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>
