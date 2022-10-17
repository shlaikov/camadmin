<script setup>
import EmptyFile from '@/Components/Icons/EmptyFile.vue'

defineProps({
  file: { type: Object, required: true },
  tag: { type: String, default: 'li' },
})

defineEmits(['remove'])
</script>

<template>
  <component :is="tag" class="file-preview">
    <button class="close-icon" @click="$emit('remove', file)">&times;</button>

    <div class="flex">
      <EmptyFile />
      <p class="mx-2">{{ file.file.name }}</p>
    </div>

    <span v-show="file.status == 'loading'" class="status-indicator loading-indicator"
      >In Progress</span
    >
    <span v-show="file.status == true" class="status-indicator success-indicator">Uploaded</span>
    <span v-show="file.status == false" class="status-indicator failure-indicator">Error</span>
  </component>
</template>

<style scoped>
.file-preview {
  margin: 1rem 2.5%;
  position: relative;
  overflow: hidden;
}

.file-preview img {
  width: 100%;
  height: 100%;
  display: block;
  object-fit: cover;
}

.file-preview .close-icon,
.file-preview .status-indicator {
  --size: 20px;
  position: absolute;
  line-height: var(--size);
  height: var(--size);
  border-radius: var(--size);
  box-shadow: 0 0 5px currentColor;
  right: 0.25rem;
  appearance: none;
  border: 0;
  padding: 0;
}
.file-preview .close-icon {
  width: var(--size);
  font-size: var(--size);
  background: #933;
  color: #fff;
  top: 0.25rem;
  cursor: pointer;
}

.file-preview .status-indicator {
  font-size: calc(0.75 * var(--size));
  bottom: 0.25rem;
  padding: 0 10px;
}
.file-preview .loading-indicator {
  animation: pulse 1.5s linear 0s infinite;
  color: #000;
}
.file-preview .success-indicator {
  background: #6c6;
  color: #040;
}
.file-preview .failure-indicator {
  background: #933;
  color: #fff;
}

@keyframes pulse {
  0% {
    background: #fff;
  }
  50% {
    background: #ddd;
  }
  100% {
    background: #fff;
  }
}
</style>
