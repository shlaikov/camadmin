<script setup>
import { onMounted, ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue'
import Camunda from '@/Repository/Camunda'
import camundaApi from '@/Api/Camunda'
import DiagramFiles from '@/Components/DiagramFiles.vue'
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

const isEditMode = ref(!instance.description)
const logs = ref([])

const form = useForm({
  description: instance.description || '',
  version: instance.version,
})

const saveDescription = () => {
  form.put(route('instances.update', props.instanceId), {
    errorBag: 'createInstance',
    preserveScroll: true,
    onSuccess: () => (isEditMode.value = false),
  })
}

onMounted(() => {
  if (!instance.error) {
    camundaApi()
      .get(`${props.instanceId}/history/activity-instance`, {
        params: {
          sortBy: 'startTime',
          sortOrder: 'desc',
          maxResults: 8,
        },
      })
      .then(({ data }) => {
        logs.value = data
      })
  }
})
</script>

<template>
  <AppLayout title="Camunda instance">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Camunda instance</h2>
    </template>

    <div v-if="instance">
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
              {{ instance.name }}
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              {{ instance.url }}
              <span
                v-show="instance.version"
                class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 rounded mr-2"
              >
                v{{ instance.version }}
              </span>
            </p>
          </div>
          <div class="border-t border-gray-200">
            <dl>
              <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd v-if="isEditMode" class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                  <textarea
                    v-model="form.description"
                    name="description"
                    rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-secondary focus:border-secondary"
                    placeholder="Write your description here..."
                  />
                  <button
                    class="mt-4 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                    @click="saveDescription"
                  >
                    Save
                  </button>
                </dd>
                <dd v-else class="relative mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                  {{ instance.description }}
                  <div class="absolute bottom-0 right-0" @click="isEditMode = true">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-6 h-6"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                      />
                    </svg>
                  </div>
                </dd>
              </div>
              <div
                v-show="instance.statistics && instance.statistics.length > 0"
                class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6"
              >
                <div v-for="data in instance.statistics" :key="data.key">
                  <DiagramFiles :file="data.diagram" />
                </div>
              </div>
              <div
                v-show="logs.length > 0"
                class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
              >
                <dt class="text-sm font-medium text-gray-500">Recent activities</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                  <ul
                    role="list"
                    class="divide-y divide-gray-200 rounded-md border border-gray-200"
                  >
                    <li
                      v-for="log in logs"
                      :key="log.id"
                      class="flex items-center justify-between py-3 pl-3 pr-4 text-sm"
                    >
                      <div class="flex w-0 flex-1 items-center">
                        <div v-html="Camunda.convertActivityTypesToIcon(log.activityType)" />
                        <span class="ml-2 w-0 flex-1 truncate">{{
                          log.activityName || log.activityId
                        }}</span>
                      </div>
                      <div class="ml-4 flex-shrink-0">
                        <span class="ml-2 w-0 flex-1 truncate">
                          {{ Camunda.convertTime(log.startTime) }} -
                          {{ Camunda.convertTime(log.endTime) || 'currently' }}
                        </span>
                      </div>
                    </li>
                    <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                      <div class="flex items-center justify-between">
                        <a class="" href="#">Show more...</a>
                      </div>
                    </li>
                  </ul>
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
