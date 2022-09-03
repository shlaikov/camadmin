<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import JetButton from '@/Components/Button.vue'
import JetFormSection from '@/Components/FormSection.vue'
import JetInput from '@/Components/Input.vue'
import JetInputError from '@/Components/InputError.vue'
import JetLabel from '@/Components/Label.vue'

const form = useForm({
  name: '',
})

const createProcess = () => {
  form.post(route('process.store'), {
    errorBag: 'createProcess',
    preserveScroll: true,
  })
}
</script>

<template>
  <JetFormSection @submitted="createProcess">
    <template #title> Team Details </template>

    <template #description> Create a new team to collaborate with others on projects. </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="name" value="Process Name" />
        <JetInput id="name" v-model="form.name" type="text" class="block w-full mt-1" autofocus />
        <JetInputError :message="form.errors.name" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Create
      </JetButton>
    </template>
  </JetFormSection>
</template>
