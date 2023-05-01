<script setup>
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

import JetButton from '@/Components/Button.vue'
import JetFormSection from '@/Components/FormSection.vue'
import JetInput from '@/Components/Input.vue'
import JetTextarea from '@/Components/Textarea.vue'
import JetInputError from '@/Components/InputError.vue'
import JetLabel from '@/Components/Label.vue'
import JetActionMessage from '@/Components/ActionMessage.vue'
import { useInstanceStore } from '@/Stores/instance'

const instanceStore = useInstanceStore()
const instances = computed(() => instanceStore.instances)

const props = defineProps({
  instanceId: {
    required: true,
    type: String,
  },
})

const instance = instances.value.find((i) => i.id == props.instanceId)

const form = useForm({
  _method: 'PUT',
  name: instance.name,
  description: instance.description,
  url: instance.url,
})

const updateInformation = () => {
  form.post(route('instances.update', { id: props.instanceId }), {
    errorBag: 'updateInformation',
    preserveScroll: true,
    onSuccess: () => {
      instanceStore.$patch((state) => {
        const instance = state.instances.find((i) => i.id == props.instanceId)

        instance.name = form.name
        instance.description = form.description
        instance.url = form.url

        state.mounted = false
      })
    },
  })
}
</script>

<template>
  <JetFormSection @submitted="updateInformation">
    <template #title> Information </template>

    <template #description> Update your instance information. </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="name" value="Name" />
        <JetInput
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full"
          autocomplete="name"
          required
        />
        <JetInputError :message="form.errors.name" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="description" value="Description" />
        <JetTextarea
          id="description"
          v-model="form.description"
          type="text"
          class="mt-1 block w-full"
        />
        <JetInputError :message="form.errors.description" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="url" value="Url" />
        <JetInput
          id="url"
          v-model="form.url"
          type="text"
          class="mt-1 block w-full"
          autocomplete="url"
          required
        />
        <JetInputError :message="form.errors.url" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <JetActionMessage :on="form.recentlySuccessful" class="mr-3"> Saved. </JetActionMessage>

      <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </JetButton>
    </template>
  </JetFormSection>
</template>
