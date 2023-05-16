<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

import JetApplicationMark from '@/Components/ApplicationMark.vue'
import JetDropdown from '@/Components/Dropdown.vue'
import JetDropdownLink from '@/Components/DropdownLink.vue'
import { useInstanceStore } from '@/Stores/instance'

const instanceStore = useInstanceStore()

const instances = computed(() => instanceStore.instances)
const instanceId = computed(() => usePage().props.instanceId)
const instance = instances.value.find((i) => i.id == instanceId.value)
</script>

<template>
  <aside class="hidden fixed sm:flex sm:flex-col top-0 left-0 w-64 bg-white h-full border-r">
    <div class="flex items-center px-5 h-16">
      <Link :href="route('dashboard')">
        <JetApplicationMark class="block h-9 w-auto" />
      </Link>
    </div>
    <div class="overflow-y-auto overflow-x-hidden flex-grow">
      <ul class="flex flex-col py-4 space-y-1">
        <li class="px-5">
          <div class="flex flex-row items-center h-8">
            <div class="text-md font-md tracking-wide text-gray-400">Main Menu</div>
          </div>
        </li>
        <li>
          <Link
            :href="route('dashboard')"
            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 text-gray-600 hover:text-gray-800 border-l-4 hover:border-secondary pr-6"
            :class="{ 'border-primary text-gray-800': route().current('dashboard') }"
          >
            <span class="inline-flex justify-center items-center ml-4">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                ></path>
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Dashboard</span>
          </Link>
        </li>
        <li class="cursor-pointer" :class="{ 'py-2 bg-gray-100': instanceId }">
          <JetDropdown align="center" width="160">
            <template #trigger>
              <div
                class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 text-gray-600 hover:text-gray-800 border-l-4 hover:border-secondary pr-6"
                :class="{ 'border-primary text-gray-800': instanceId }"
              >
                <span class="inline-flex justify-center items-center ml-4">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-5 h-5"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z"
                    />
                  </svg>
                </span>
                <span class="ml-2 text-sm tracking-wide truncate">{{
                  instance ? instance.name : 'Instances'
                }}</span>
                <span class="px-2 py-0.5 ml-auto">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                    />
                  </svg>
                </span>
              </div>
            </template>

            <template #content>
              <div class="w-64">
                <template v-if="instances.length > 0">
                  <JetDropdownLink
                    v-for="instanceData in instances"
                    :key="instanceData.id"
                    :href="route('instances.show', instanceData.id)"
                    :aria-current="route().current('instances.show', instanceData.id) ? 'page' : ''"
                  >
                    {{ instanceData.name }}
                  </JetDropdownLink>
                  <div class="border-t border-gray-100" />
                </template>
                <a
                  :href="route('instances.create')"
                  class="hover:bg-gray-100 focus:bg-gray-100 leading-5 group flex items-center text-gray-700 text-sm w-full px-2 py-2 transition"
                >
                  <svg width="20" height="20" fill="currentColor" class="mr-2" aria-hidden="true">
                    <path
                      d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1Z"
                    />
                  </svg>
                  {{ __('header.menu.instances.create') }}
                </a>
              </div>
            </template>
          </JetDropdown>
          <div v-if="instanceId" class="px-5 my-4">
            <ul class="flex-grow my-2 space-y-4">
              <li>
                <Link
                  :href="route('instances.logs', { id: instanceId })"
                  class="flex text-sm font-md tracking-wide items-center text-gray-500 hover:text-gray-700"
                  :class="{
                    'text-gray-900 hover:text-gray-500': route().current('instances.logs'),
                  }"
                  :aria-current="route().current('instances.logs') ? 'page' : ''"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4 mr-1"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"
                    />
                  </svg>
                  Logs
                </Link>
              </li>
              <li>
                <Link
                  :href="route('instances.settings', { id: instanceId })"
                  class="flex text-sm font-md tracking-wide items-center text-gray-500 hover:text-gray-700"
                  :class="{
                    'text-gray-900 hover:text-gray-500': route().current('instances.settings'),
                  }"
                  :aria-current="route().current('instances.settings') ? 'page' : ''"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4 mr-1"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                  </svg>
                  Settings
                </Link>
              </li>
            </ul>
          </div>
        </li>
        <li>
          <Link
            :href="route('current-team.members')"
            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 text-gray-600 hover:text-gray-800 border-l-4 hover:border-secondary pr-6"
            :class="{ 'border-primary text-gray-800': route().current('current-team.members') }"
          >
            <span class="inline-flex justify-center items-center ml-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-5 h-5"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                />
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Team members</span>
          </Link>
        </li>
        <li>
          <Link
            :href="route('diagrams')"
            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 text-gray-600 hover:text-gray-800 border-l-4 hover:border-secondary pr-6"
            :class="{ 'border-primary text-gray-800': route().current('diagrams') }"
          >
            <span class="inline-flex justify-center items-center ml-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-5 h-5"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122"
                />
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Diagrams</span>
          </Link>
        </li>
        <li class="px-5">
          <div class="flex flex-row items-center h-8">
            <div class="text-md font-md tracking-wide text-gray-400">Access</div>
          </div>
        </li>
        <li>
          <Link
            :href="route('api-tokens.index')"
            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 text-gray-600 hover:text-gray-800 border-l-4 hover:border-secondary pr-6"
            :class="{ 'border-primary text-gray-800': route().current('api-tokens.index') }"
          >
            <span class="inline-flex justify-center items-center ml-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-5 h-5"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z"
                />
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">API Tokens</span>
          </Link>
        </li>
        <li class="px-5">
          <div class="flex flex-row items-center h-8">
            <div class="text-md font-md tracking-wide text-gray-400">Preferences</div>
          </div>
        </li>
        <li>
          <Link
            :href="route('profile.show')"
            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 text-gray-600 hover:text-gray-800 border-l-4 hover:border-secondary pr-6"
            :class="{ 'border-primary text-gray-800': route().current('profile.show') }"
          >
            <span class="inline-flex justify-center items-center ml-4">
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                ></path>
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Profile</span>
          </Link>
        </li>
        <li>
          <Link
            :href="route('teams.show', $page.props.user.current_team)"
            class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 text-gray-600 hover:text-gray-800 border-l-4 hover:border-secondary pr-6"
            :class="{ 'border-primary text-gray-800': route().current('teams.show') }"
          >
            <span class="inline-flex justify-center items-center ml-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-5 h-5"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                />
              </svg>
            </span>
            <span class="ml-2 text-sm tracking-wide truncate">Team</span>
          </Link>
        </li>
      </ul>
    </div>
  </aside>
</template>
