<script setup>
import { computed } from 'vue'

import AppLayout from '@/Layouts/AppLayout.vue'
import { useInstanceStore } from '@/Stores/instance'
import Navbar from './Navbar.vue'

const instanceStore = useInstanceStore()
const instances = computed(() => instanceStore.instances)

const props = defineProps({
  title: {
    required: true,
    type: String,
  },
  instanceId: {
    required: true,
    type: String,
  },
})

const instance = instances.value.find((i) => i.id == props.instanceId)
</script>

<template>
  <AppLayout :title="title">
    <template #header>
      <Navbar :instance-id="instanceId" />
    </template>

    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden border border-gray-200 shadow-sm sm:rounded-lg">
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
            <section v-if="instance.error" class="py-10 bg-neutral-50 overflow-hidden">
              <div class="container px-4 mx-auto">
                <img class="mx-auto" src="dashy-assets/images/empty.png" alt="" />
                <div class="max-w-md mx-auto text-center">
                  <h2 class="font-heading mb-3 text-2xl font-semibold">Something went wrong</h2>
                  <p class="mb-7 text-neutral-500">
                    In the settings page you will be able to see the error and the reason for
                    connecting to the server.
                  </p>
                  <a
                    class="inline-flex items-center px-6 py-2.5 text-md text-neutral-50 font-medium bg-primary hover:bg-secondary rounded-lg transition duration-300"
                    :href="route('instances.settings', { id: instanceId })"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"
                      />
                    </svg>
                    Try to repair
                  </a>
                </div>
              </div>
            </section>
            <slot v-else name="content" />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
