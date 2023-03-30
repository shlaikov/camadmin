<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'

import JetApplicationLogo from '@/Components/ApplicationLogo.vue'
import DiagramFiles from '@/Components/DiagramFiles.vue'
import { useUserStore } from '@/Stores/user'
import { useInstanceStore } from '@/Stores/instance'

const userStore = useUserStore()
const instanceStore = useInstanceStore()

const user = computed(() => userStore.user)
const instances = computed(() => instanceStore.instances)

const { instanceStatistics } = storeToRefs(instanceStore)
</script>

<template>
  <div>
    <div class="flex p-6 sm:px-20 bg-white border-b border-gray-200">
      <div>
        <JetApplicationLogo class="block h-12 w-auto" />
        <div class="inline-block mt-8 text-xl">
          {{ __('welcome') }},
          <span class="rounded-full ring-2 ring-white">
            <img
              class="inline-block h-8 w-8 rounded-full ring-2 ring-white"
              :src="user.profile_photo_url"
              :alt="user.name"
            />
            {{ user.name }}
          </span>
        </div>
      </div>

      <div v-if="instanceStatistics" class="flex w-full space-x-4 justify-end">
        <div class="rounded-lg px-6 py-6 ring-1 ring-slate-900/5 shadow-sm">
          <div>
            <span
              class="inline-flex items-center justify-center p-2 bg-gray-200 rounded-md shadow-lg"
              :class="{ 'bg-green-200': instanceStatistics.runningInstances > 0 }"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-slate-900"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"
                />
              </svg>
            </span>
          </div>
          <h3 class="text-slate-900 mt-5 text-base font-medium tracking-tight">
            Running instances: {{ instanceStatistics.runningInstances }}
          </h3>
        </div>
        <div class="rounded-lg px-6 py-6 ring-1 ring-slate-900/5 shadow-sm">
          <div>
            <span
              class="inline-flex items-center justify-center p-2 bg-gray-200 rounded-md shadow-lg"
              :class="{ 'bg-red-200 ': instanceStatistics.incidents > 0 }"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-slate-900"
                :class="{ 'text-red-400': instanceStatistics.incidents > 0 }"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                />
              </svg>
            </span>
          </div>
          <h3 class="text-slate-900 mt-5 text-base font-medium tracking-tight">
            Incidents: {{ instanceStatistics.incidents }}
          </h3>
        </div>
        <div class="rounded-lg px-6 py-6 ring-1 ring-slate-900/5 shadow-sm">
          <div>
            <span
              class="inline-flex items-center justify-center p-2 bg-gray-200 rounded-md shadow-lg"
              :class="{ 'bg-red-200 ': instanceStatistics.failedJobs > 0 }"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-slate-900"
                :class="{ 'text-red-400': instanceStatistics.failedJobs > 0 }"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                />
              </svg>
            </span>
          </div>
          <h3 class="text-slate-900 mt-5 text-base font-medium tracking-tight">
            Failed jobs: {{ instanceStatistics.failedJobs }}
          </h3>
        </div>
      </div>
    </div>

    <div class="mt-2 bg-gray-200 bg-opacity-25 grid grid-cols-1">
      <section>
        <header class="bg-white space-y-4 p-6 sm:px-20">
          <div class="flex items-center justify-between">
            <h2 class="font-semibold text-slate-900">Projects</h2>
            <a
              :href="route('instances.create')"
              class="hover:bg-indigo-400 group flex items-center rounded-md bg-indigo-500 text-white text-sm font-medium pl-2 pr-3 py-2 shadow-sm transition"
            >
              <svg width="20" height="20" fill="currentColor" class="mr-2" aria-hidden="true">
                <path
                  d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1Z"
                />
              </svg>
              Add new instance
            </a>
          </div>
          <form class="group relative">
            <svg
              width="20"
              height="20"
              fill="currentColor"
              class="absolute left-3 top-1/2 -mt-2.5 text-slate-400 pointer-events-none"
              aria-hidden="true"
            >
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
              />
            </svg>
            <input
              class="w-full text-sm leading-6 text-slate-900 placeholder-slate-400 py-2 pl-10 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
              type="text"
              aria-label="Filter projects"
              placeholder="Filter projects..."
            />
          </form>
        </header>
        <ul
          class="bg-slate-50 p-6 sm:px-20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4 text-sm leading-6"
        >
          <li v-for="instance in instances" :key="instance.id" class="flex">
            <a
              :href="route('instances.show', instance.id)"
              class="w-full border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 group rounded-md p-3 bg-white ring-1 ring-slate-200 shadow-sm transition"
              :class="{
                'hover:shadow-md focus:border-indigo-300 ': !instance.error,
                'bg-red-200 focus:ring-red-200': instance.error,
              }"
            >
              <dl class="grid sm:block lg:grid xl:block grid-cols-2 grid-rows-2 items-center">
                <div>
                  <dt class="sr-only">Title</dt>
                  <dd class="group-hover:text-indigo-800 font-semibold text-slate-900">
                    {{ instance.name }}
                  </dd>
                </div>
                <div>
                  <dt class="sr-only">Url</dt>
                  <dd class="group-hover:text-indigo-800">{{ instance.url }}</dd>
                </div>

                <div v-if="instance.error">
                  <span
                    class="bg-red-300 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-3 h-3"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                      /></svg
                    >&nbsp; Connection error
                  </span>
                </div>
                <div v-else>
                  <span
                    v-if="instance.statistics && instance.statistics.length > 0"
                    class="bg-green-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
                  >
                    {{ instance.statistics.reduce((sum, i) => sum + i.instances, 0) }}
                    in process
                  </span>
                  <span
                    v-if="instance.statistics && instance.statistics.length > 0"
                    class="bg-slate-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
                    :class="{
                      'bg-red-100':
                        instance.statistics.reduce((sum, i) => sum + i.failedJobs, 0) > 0,
                    }"
                  >
                    {{ instance.statistics.reduce((sum, i) => sum + i.failedJobs, 0) }} failed
                  </span>
                </div>
              </dl>
            </a>
          </li>
          <li class="flex">
            <a
              :href="route('instances.create')"
              class="hover:border-indigo-500 hover:border-solid hover:bg-white group w-full flex flex-col items-center justify-center rounded-md border-2 border-dashed border-slate-300 text-sm leading-6 text-slate-900 font-medium py-3 transition"
            >
              <svg
                class="group-hover:text-indigo-500 mb-1 text-slate-400"
                width="20"
                height="20"
                fill="currentColor"
                aria-hidden="true"
              >
                <path
                  d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1Z"
                />
              </svg>
              Add new instance
            </a>
          </li>
        </ul>
      </section>
      <section v-if="$page.props.recent_work.length > 0">
        <div class="bg-white space-y-4 p-6 sm:px-20">
          <div class="flex items-center justify-between">
            <h2 class="font-semibold text-slate-900">Recent work</h2>
          </div>
        </div>
        <div class="relative bg-white mx-auto pb-6 px-20 flex flex-wrap align-middle">
          <div
            v-for="diagram in $page.props.recent_work"
            :key="diagram.id"
            class="px-1 w-full md:w-1/2 lg:my-2 lg:w-1/4"
          >
            <DiagramFiles :file="diagram" />
          </div>
        </div>
      </section>
    </div>
  </div>
</template>
