<script setup>
import { computed, onMounted, ref } from 'vue'

import camundaApi from '@/Api/Camunda'
import { useInstanceStore } from '@/Stores/instance'
import Layout from './Partials/Layout.vue'

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
        },
      })
      .then(({ data }) => {
        logs.value = data
      })
  }
})
</script>

<template>
  <Layout title="Logs" :instance-id="instanceId">
    <template #content>
      <section class="bg-gray-50 overflow-x-scroll">
        <table class="table-auto w-full bg-white rounded">
          <thead class="border-b border-gray-100">
            <tr>
              <th class="pl-6 py-6">
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>ID</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Definition Key</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Activity ID</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Start Time</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Canceled</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Complete Scope</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>End Time</p>
                </a>
              </th>
            </tr>
          </thead>
          <tbody v-for="(log, key) in logs" :key="log.id">
            <tr
              class="text-xs border-gray-100"
              :class="{ 'bg-purple-50 border-gray-100': key % 2 === 1 }"
            >
              <td class="pl-6 py-3">{{ log.id }}</td>
              <td
                class="pl-3 py-3 font-bold"
                :class="{
                  'bg-purple-100': key % 2 === 1,
                  'bg-purple-50': key % 2 !== 1,
                }"
              >
                {{ log.processDefinitionKey }}
              </td>
              <td class="pl-3">{{ log.activityId }}</td>
              <td>{{ log.startTime }}</td>
              <td>{{ log.canceled }}</td>
              <td>{{ log.completeScope }}</td>
              <td>{{ log.endTime }}</td>
            </tr>
          </tbody>
        </table>
      </section>
    </template>
  </Layout>
</template>
