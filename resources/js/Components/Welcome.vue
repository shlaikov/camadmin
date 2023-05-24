<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import {
  Chart as ChartJS,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  TimeSeriesScale,
  LinearScale,
} from 'chart.js'
import { Doughnut, Bar } from 'vue-chartjs'

import DiagramFiles from '@/Components/DiagramFiles.vue'
import { useInstanceStore } from '@/Stores/instance'

ChartJS.register(
  ArcElement,
  Title,
  Tooltip,
  TimeSeriesScale,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
)

const instanceStore = useInstanceStore()
const instances = computed(() => instanceStore.instances)

const { instanceStatistics, instanceActivity } = storeToRefs(instanceStore)
</script>

<template>
  <div>
    <div class="block sm:flex p-6 sm:px-20 bg-white border-b border-gray-200">
      <div class="block w-full rounded-lg px-6 py-6 ring-1 ring-slate-900/5 shadow-sm mr-4">
        <Bar
          :options="{
            responsive: true,
            interaction: {
              intersect: false,
            },
            plugins: {
              title: {
                display: true,
                text: 'Last 6 month activity in Instances',
              },
              legend: false,
            },
          }"
          :data="instanceActivity"
        />
      </div>

      <div
        v-if="instanceStatistics"
        class="block sm:flex w-full mt-4 sm:mt-0 sm:space-x-4 justify-end"
      >
        <div class="rounded-lg px-6 py-6 ring-1 ring-slate-900/5 shadow-sm">
          <Doughnut
            :data="{
              labels: ['Running', 'Errors'],
              datasets: [
                {
                  backgroundColor: ['#7bed9f', '#e74c3c'],
                  data: [instanceStatistics.runningInstances, instanceStatistics.failedJobs],
                },
              ],
            }"
            :options="{
              responsive: true,
              maintainAspectRatio: false,
            }"
          />
        </div>
        <div class="inline-block space-y-2 w-full sm:w-auto sm:space-y-4 my-2 sm:my-0">
          <div class="rounded-lg px-6 py-6 ring-1 ring-slate-900/5 shadow-sm">
            <div>
              <span
                class="inline-flex items-center justify-center p-2 bg-gray-200 rounded-md shadow-lg"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="h-6 w-6 text-slate-900"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"
                  />
                </svg>
              </span>
            </div>
            <h3 class="text-slate-900 mt-5 text-base font-medium tracking-tight">
              Instances: {{ instances.length }}
            </h3>
          </div>
          <div class="rounded-lg px-6 py-6 ring-1 ring-slate-900/5 shadow-sm">
            <div>
              <span
                class="inline-flex items-center justify-center p-2 bg-gray-200 rounded-md shadow-lg"
                :class="{ 'bg-warning-lighter': instanceStatistics.incidents > 0 }"
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
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="h-6 w-6 text-slate-900"
                  :class="{ 'text-red-400': instanceStatistics.failedJobs > 0 }"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0112 12.75zm0 0c2.883 0 5.647.508 8.207 1.44a23.91 23.91 0 01-1.152 6.06M12 12.75c-2.883 0-5.647.508-8.208 1.44.125 2.104.52 4.136 1.153 6.06M12 12.75a2.25 2.25 0 002.248-2.354M12 12.75a2.25 2.25 0 01-2.248-2.354M12 8.25c.995 0 1.971-.08 2.922-.236.403-.066.74-.358.795-.762a3.778 3.778 0 00-.399-2.25M12 8.25c-.995 0-1.97-.08-2.922-.236-.402-.066-.74-.358-.795-.762a3.734 3.734 0 01.4-2.253M12 8.25a2.25 2.25 0 00-2.248 2.146M12 8.25a2.25 2.25 0 012.248 2.146M8.683 5a6.032 6.032 0 01-1.155-1.002c.07-.63.27-1.222.574-1.747m.581 2.749A3.75 3.75 0 0115.318 5m0 0c.427-.283.815-.62 1.155-.999a4.471 4.471 0 00-.575-1.752M4.921 6a24.048 24.048 0 00-.392 3.314c1.668.546 3.416.914 5.223 1.082M19.08 6c.205 1.08.337 2.187.392 3.314a23.882 23.882 0 01-5.223 1.082"
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
    </div>

    <div class="mt-2 bg-gray-200 bg-opacity-25 grid grid-cols-1">
      <section>
        <header class="bg-white space-y-4 p-6 sm:px-20">
          <div class="flex items-center justify-between">
            <h2 class="font-semibold text-slate-900">Camunda Instances</h2>
            <a
              :href="route('instances.create')"
              class="hover:bg-secondary group flex items-center rounded-md bg-primary text-white text-sm font-medium pl-2 pr-3 py-2 shadow-sm transition"
            >
              <svg width="20" height="20" fill="currentColor" class="mr-2" aria-hidden="true">
                <path
                  d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1Z"
                />
              </svg>
              Add new instance
            </a>
          </div>
          <!-- <form class="group relative">
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
              class="w-full text-sm leading-6 text-slate-900 placeholder-slate-400 py-2 pl-10 border-gray-300 focus:border-secondary focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md shadow-sm"
              type="text"
              aria-label="Filter projects"
              placeholder="Filter projects..."
            />
          </form> -->
        </header>
        <ul
          class="bg-slate-50 p-6 sm:px-20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4 text-sm leading-6"
        >
          <li v-for="instance in instances" :key="instance.id" class="flex">
            <a
              :href="route('instances.show', instance.id)"
              class="w-full border-gray-300 focus:ring focus:ring-secondary focus:ring-opacity-50 group rounded-md p-3 bg-white ring-1 ring-slate-200 shadow-sm transition"
              :class="{
                'hover:shadow-md focus:border-secondary': !instance.error,
                'bg-error focus:ring-error-lighter': instance.error,
              }"
            >
              <dl class="grid sm:block lg:grid xl:block sm:grid-cols-2 grid-rows-2 items-center">
                <div>
                  <dt class="sr-only">Title</dt>
                  <dd class="group-hover:text-primary font-semibold text-slate-900">
                    {{ instance.name }}
                  </dd>
                </div>
                <div>
                  <dt class="sr-only">Url</dt>
                  <dd class="group-hover:text-primary truncate">{{ instance.url }}</dd>
                </div>

                <div v-if="instance.error">
                  <span
                    class="bg-error text-white text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
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
                    class="bg-success text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
                  >
                    {{ instance.statistics.reduce((sum, i) => sum + i.instances, 0) }}
                    in process
                  </span>
                  <span
                    v-if="instance.statistics && instance.statistics.length > 0"
                    class="bg-slate-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded ml-0 mr-2"
                    :class="{
                      'bg-error': instance.statistics.reduce((sum, i) => sum + i.failedJobs, 0) > 0,
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
              class="hover:border-secondary hover:border-solid hover:bg-white group w-full flex flex-col items-center justify-center rounded-md border-2 border-dashed border-slate-300 text-sm leading-6 text-slate-900 font-medium py-3 transition"
            >
              <svg
                class="group-hover:text-primary mb-1 text-slate-400"
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
