<script setup>
import { computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

import JetButton from '@/Components/Button.vue'
import JetFormSection from '@/Components/FormSection.vue'
import JetInput from '@/Components/Input.vue'
import JetSelect from '@/Components/Select.vue'
import JetInputError from '@/Components/InputError.vue'
import JetLabel from '@/Components/Label.vue'

const diagramTypes = computed(() => usePage().props.diagram_types)

const form = useForm({
  name: '',
  type: diagramTypes.value[0].value,
})

const createDiagram = () => {
  form.post(route('diagrams.store'), {
    errorBag: 'createDiagram',
    preserveScroll: true,
  })
}
</script>

<template>
  <JetFormSection @submitted="createDiagram">
    <template #title> Diagram Details </template>

    <template #description> Create a new diagram to collaborate with others on projects. </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="name" value="Name" />
        <JetInput id="name" v-model="form.name" type="text" class="block w-full mt-1" autofocus />
        <JetInputError :message="form.errors.name" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <JetLabel for="name" value="Type" />
        <JetSelect
          id="type"
          v-model="form.type"
          :options="$page.props.diagram_types"
          class="block w-full mt-1"
        />
        <JetInputError :message="form.errors.type" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Create
      </JetButton>
    </template>
  </JetFormSection>
</template>
