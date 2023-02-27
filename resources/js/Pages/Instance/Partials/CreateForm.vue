<script setup>
import { useForm } from '@inertiajs/vue3'

import JetButton from '@/Components/Button.vue'
import JetFormSection from '@/Components/FormSection.vue'
import JetInput from '@/Components/Input.vue'
import JetInputError from '@/Components/InputError.vue'
import JetLabel from '@/Components/Label.vue'
import JetCheckbox from '@/Components/Checkbox.vue'

const form = useForm({
  name: '',
  type: 'camunda',
  authentication_type: 'bearer',
  url: '',
  tenant_id: null,
  token: '',
  has_counter: false,
})

const addInstance = () => {
  form.post(route('instances.store'), {
    errorBag: 'createInstance',
    preserveScroll: true,
  })
}
</script>

<template>
  <JetFormSection @submitted="addInstance">
    <template #title> Camunda instance Details </template>

    <template #description> Add a new instance to collaborate with others on projects. </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="name" value="Name" />
        <JetInput
          id="name"
          v-model="form.name"
          type="text"
          class="block w-full mt-1"
          required
          autofocus
        />
        <JetInputError :message="form.errors.name" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="url" value="Engine url" />
        <JetInput id="url" v-model="form.url" type="text" class="block w-full mt-1" required />
        <JetInputError :message="form.errors.url" class="mt-2" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="tenantId" value="TenantId" />
        <JetInput id="tenantId" v-model="form.tenantId" type="text" class="block w-full mt-1" />
        <JetInputError :message="form.errors.url" class="mt-2" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <JetCheckbox v-model:checked="form.has_counter" name="has_counter" />
        <span class="ml-2 text-sm text-gray-600">Process definition counters</span>
        <JetInputError :message="form.errors.has_counter" class="mt-2" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="token" value="Bearer token" />
        <JetInput id="token" v-model="form.token" type="text" class="block w-full mt-1" required />
        <JetInputError :message="form.errors.token" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Add
      </JetButton>
    </template>
  </JetFormSection>
</template>
