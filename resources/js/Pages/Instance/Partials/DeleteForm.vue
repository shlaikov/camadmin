<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import JetActionSection from '@/Components/ActionSection.vue'
import JetDialogModal from '@/Components/DialogModal.vue'
import JetDangerButton from '@/Components/DangerButton.vue'
import JetInputError from '@/Components/InputError.vue'
import JetSecondaryButton from '@/Components/SecondaryButton.vue'

const confirmingDeletion = ref(false)

const props = defineProps({
  instanceId: {
    required: true,
    type: String,
  },
})

const form = useForm({})

const confirmDeletion = () => {
  confirmingDeletion.value = true
}

const deleteInstance = () => {
  form.delete(route('instances.delete', props.instanceId), {
    preserveScroll: true,
    onSuccess: () => {
      closeModal()

      return (window.location = route('dashboard'))
    },
    onError: () => form.reset(),
    onFinish: () => form.reset(),
  })
}

const closeModal = () => {
  confirmingDeletion.value = false

  form.reset()
}
</script>

<template>
  <JetActionSection>
    <template #title> Delete Instance </template>

    <template #description> Permanently delete your instance. </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600">
        Once your instance is deleted, all of its resources and data will be permanently deleted.
        Before deleting your instance, please download any data or information that you wish to
        retain.
      </div>

      <div class="mt-5">
        <JetDangerButton @click="confirmDeletion"> Delete Instance </JetDangerButton>
      </div>

      <JetDialogModal :show="confirmingDeletion" @close="closeModal">
        <template #title> Delete Instance </template>

        <template #content>
          Are you sure you want to delete your camunda instance?

          <div class="mt-4">
            <JetInputError :message="form.errors.confirmingDeletion" class="mt-2" />
          </div>
        </template>

        <template #footer>
          <JetSecondaryButton @click="closeModal"> Cancel </JetSecondaryButton>

          <JetDangerButton
            class="ml-3"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="deleteInstance"
          >
            Delete Instance
          </JetDangerButton>
        </template>
      </JetDialogModal>
    </template>
  </JetActionSection>
</template>
