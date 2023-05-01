<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import JetActionSection from '@/Components/ActionSection.vue'
import JetDialogModal from '@/Components/DialogModal.vue'
import JetDangerButton from '@/Components/DangerButton.vue'
import JetInput from '@/Components/Input.vue'
import JetInputError from '@/Components/InputError.vue'
import JetSecondaryButton from '@/Components/SecondaryButton.vue'

const confirmingUserDeletion = ref(false)
const passwordInput = ref(null)

const form = useForm({
  confirmationText: '',
})

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true

  setTimeout(() => passwordInput.value.focus(), 250)
}

const deleteInstance = () => {
  form.delete(route('instances.delete'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  })
}

const closeModal = () => {
  confirmingUserDeletion.value = false

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
        <JetDangerButton @click="confirmUserDeletion"> Delete Instance </JetDangerButton>
      </div>

      <JetDialogModal :show="confirmingUserDeletion" @close="closeModal">
        <template #title> Delete Instance </template>

        <template #content>
          Are you sure you want to delete your account? Once your account is deleted, all of its
          resources and data will be permanently deleted. Please enter your password to confirm you
          would like to permanently delete your account.

          <div class="mt-4">
            <JetInput
              ref="passwordInput"
              v-model="form.password"
              type="password"
              class="mt-1 block w-3/4"
              placeholder="Password"
              @keyup.enter="deleteInstance"
            />

            <JetInputError :message="form.errors.password" class="mt-2" />
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
