<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

import JetApplicationLogo from '@/Components/ApplicationLogo.vue'
import DiagramFiles from '@/Components/DiagramFiles.vue'
import { useUserStore } from '@/Stores/user'

const userStore = useUserStore()

const user = computed(() => userStore.user)
const instances = computed(() => usePage().props.instances)
</script>

<template>
  <div>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
      <div>
        <JetApplicationLogo class="block h-12 w-auto" />
      </div>

      <div class="mt-8 text-xl">
        Welcome,
        <span class="rounded-full ring-2 ring-white">
          <img
            class="inline-block h-8 w-8 rounded-full ring-2 ring-white"
            :src="user.profile_photo_url"
            :alt="user.name"
          />
          {{ user.name }}</span
        >
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
              Add new camunda instance
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
              class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 hover:shadow-md group rounded-md p-3 bg-white ring-1 ring-slate-200 shadow-sm transition"
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
              Add new camunda instance
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
