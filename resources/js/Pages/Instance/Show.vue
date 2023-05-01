<script setup>
import { onMounted, ref, computed } from 'vue'

import Camunda from '@/Repository/Camunda'
import camundaApi from '@/Api/Camunda'
import DiagramFiles from '@/Components/DiagramFiles.vue'
import { useInstanceStore } from '@/Stores/instance'
import Layout from './Partials/Layout.vue'
import DoughnutsPanel from './Partials/DoughnutsPanel.vue'

const instanceStore = useInstanceStore()
const instances = computed(() => instanceStore.instances)

const props = defineProps({
  instanceId: {
    required: true,
    type: String,
  },
})

const instance = instances.value.find((i) => i.id == props.instanceId)
const logs = ref([])

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
  <Layout :title="instance.name" :instance-id="instanceId">
    <template #content>
      <div v-if="instance.statistics" class="py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <DoughnutsPanel :instance="instance" />
      </div>
      <div
        v-show="instance.statistics && instance.statistics.length > 0"
        class="bg-gray-50 px-4 py-5 sm:grid space-y-4 sm:space-y-0 sm:grid-cols-4 sm:gap-4 sm:px-6"
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
          <ul role="list" class="divide-y divide-gray-200 rounded-md border border-gray-200">
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
                  {{ Camunda.convertTime(log.startTime) }}

                  {{ log.endTime ? '- ' + Camunda.convertTime(log.endTime) : '' }}
                </span>
              </div>
            </li>
            <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
              <div class="flex items-center justify-between">
                <a :href="route('instances.logs', { id: instance.id })">Show more...</a>
              </div>
            </li>
          </ul>
        </dd>
      </div>
    </template>
  </Layout>
</template>
