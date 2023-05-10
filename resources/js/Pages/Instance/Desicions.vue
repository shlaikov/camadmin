<script setup>
import { computed, onMounted, ref } from 'vue'

import Loader from '@/Components/Loader.vue'
import Layout from './Partials/Layout.vue'
import camundaApi from '@/Api/Camunda'
import { useInstanceStore } from '@/Stores/instance'

const instanceStore = useInstanceStore()
const instances = computed(() => instanceStore.instances)

const props = defineProps({
  instanceId: {
    required: true,
    type: String,
  },
})

const isLoading = ref(true)

const instance = instances.value.find((i) => i.id == props.instanceId)
const desicions = ref([])

onMounted(() => {
  if (!instance.error) {
    camundaApi()
      .get(`${props.instanceId}/decision-definition`, {
        params: {
          latestVersion: true,
          sortOrder: 'asc',
          sortBy: 'name',
          firstResult: 0,
          maxResults: 50,
        },
      })
      .then(({ data }) => {
        desicions.value = data
        isLoading.value = false
      })
  }
})
</script>

<template>
  <Layout title="Desicions" :instance-id="instanceId">
    <template #content>
      <section class="bg-gray-50 overflow-x-scroll">
        <table v-if="!isLoading" class="table-auto w-full bg-white rounded">
          <thead class="border-b border-gray-100">
            <tr>
              <th class="pl-6 py-6">
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>ID</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Decision Requirements Definition Key</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Name</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Latest Version</p>
                </a>
              </th>
              <th>
                <a class="flex items-center text-xs text-gray-500" href="#">
                  <p>Tenant ID</p>
                </a>
              </th>
            </tr>
          </thead>
          <tbody v-for="(item, key) in desicions" :key="item.id">
            <tr
              class="text-xs border-gray-100"
              :class="{ 'bg-purple-50 border-gray-100': key % 2 === 1 }"
            >
              <td class="pl-6 py-3">{{ item.id }}</td>
              <td>{{ item.decisionRequirementsDefinitionKey }}</td>
              <td
                class="pl-3 py-3 font-bold"
                :class="{
                  'bg-purple-100': key % 2 === 1,
                  'bg-purple-50': key % 2 !== 1,
                }"
              >
                {{ item.name }}
              </td>
              <td class="text-center">{{ item.version }}</td>
              <td>{{ item.tenantId || '-' }}</td>
            </tr>
          </tbody>
        </table>
        <Loader v-else />
      </section>
    </template>
  </Layout>
</template>
