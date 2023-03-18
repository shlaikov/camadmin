<script setup>
import { ref, computed, onMounted } from 'vue'
import { router, Head, Link } from '@inertiajs/vue3'

import JetApplicationMark from '@/Components/ApplicationMark.vue'
import JetBanner from '@/Components/Banner.vue'
import JetDropdown from '@/Components/Dropdown.vue'
import JetDropdownLink from '@/Components/DropdownLink.vue'
import JetNavLink from '@/Components/NavLink.vue'
import JetResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { useInstanceStore } from '@/Stores/instance'

const instanceStore = useInstanceStore()

const instances = computed(() => instanceStore.instances)

defineProps({
  title: String,
})

onMounted(() => {
  instances.value.forEach((instance) => {
    instanceStore.fetchDefenitions(instance.id)
  })
})

const showingNavigationDropdown = ref(false)

const switchToTeam = (team) => {
  router.put(
    route('current-team.update'),
    {
      team_id: team.id,
    },
    {
      preserveState: false,
    }
  )
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<template>
  <div>
    <Head :title="title" />

    <JetBanner />

    <div class="flex flex-col min-h-screen bg-gray-100 justify-between">
      <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <JetApplicationMark class="block h-9 w-auto" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <JetNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                  Dashboard
                </JetNavLink>

                <JetNavLink>
                  <JetDropdown align="center" width="160">
                    <template #trigger>
                      <button
                        class="flex items-center gap-x-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300"
                        aria-expanded="false"
                      >
                        Instances
                        <svg
                          class="h-5 w-5 flex-none text-gray-400"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                          aria-hidden="true"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </template>

                    <template #content>
                      <div class="w-60">
                        <template v-if="instances.length > 0">
                          <JetDropdownLink
                            v-for="instance in instances"
                            :key="instance.id"
                            :href="route('instances.show', instance.id)"
                          >
                            {{ instance.name }}
                          </JetDropdownLink>
                          <div class="border-t border-gray-100" />
                          <a
                            :href="route('instances.create')"
                            class="hover:bg-gray-100 focus:bg-gray-100 leading-5 group flex items-center text-gray-700 text-sm w-full px-2 py-2 transition"
                          >
                            <svg
                              width="20"
                              height="20"
                              fill="currentColor"
                              class="mr-2"
                              aria-hidden="true"
                            >
                              <path
                                d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1Z"
                              />
                            </svg>
                            Add new camunda instance
                          </a>
                        </template>
                      </div>
                    </template>
                  </JetDropdown>
                </JetNavLink>

                <JetNavLink :href="route('projects')" :active="route().current('projects')">
                  Projects
                </JetNavLink>
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <div class="ml-3 relative">
                <!-- Teams Dropdown -->
                <JetDropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:bg-gray-50 active:bg-gray-50 transition"
                      >
                        {{ $page.props.user.current_team.name }}

                        <svg
                          class="ml-2 -mr-0.5 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <div class="w-60">
                      <!-- Team Management -->
                      <template v-if="$page.props.jetstream.hasTeamFeatures">
                        <div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>

                        <!-- Team Settings -->
                        <JetDropdownLink :href="route('teams.show', $page.props.user.current_team)">
                          Team Settings
                        </JetDropdownLink>

                        <JetDropdownLink
                          v-if="$page.props.jetstream.canCreateTeams"
                          :href="route('teams.create')"
                        >
                          Create New Team
                        </JetDropdownLink>

                        <div class="border-t border-gray-100" />

                        <!-- Team Switcher -->
                        <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

                        <template v-for="team in $page.props.user.all_teams" :key="team.id">
                          <form @submit.prevent="switchToTeam(team)">
                            <JetDropdownLink as="button">
                              <div class="flex items-center">
                                <svg
                                  v-if="team.id == $page.props.user.current_team_id"
                                  class="mr-2 h-5 w-5 text-green-400"
                                  fill="none"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  stroke="currentColor"
                                  viewBox="0 0 24 24"
                                >
                                  <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>{{ team.name }}</div>
                              </div>
                            </JetDropdownLink>
                          </form>
                        </template>
                      </template>
                    </div>
                  </template>
                </JetDropdown>
              </div>

              <!-- Settings Dropdown -->
              <div class="ml-3 relative">
                <JetDropdown align="right" width="48">
                  <template #trigger>
                    <button
                      v-if="$page.props.jetstream.managesProfilePhotos"
                      class="flex text-sm border-2 border-transparent rounded-full focus:border-gray-300 transition"
                    >
                      <img
                        class="h-8 w-8 rounded-full object-cover"
                        :src="$page.props.user.profile_photo_url"
                        :alt="$page.props.user.name"
                      />
                    </button>

                    <span v-else class="inline-flex rounded-md">
                      <button
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 transition"
                      >
                        {{ $page.props.user.name }}

                        <svg
                          class="ml-2 -mr-0.5 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">Manage Account</div>

                    <JetDropdownLink :href="route('profile.show')"> Profile </JetDropdownLink>

                    <JetDropdownLink
                      v-if="$page.props.jetstream.hasApiFeatures"
                      :href="route('api-tokens.index')"
                    >
                      API Tokens
                    </JetDropdownLink>

                    <div class="border-t border-gray-100" />

                    <!-- Authentication -->
                    <form @submit.prevent="logout">
                      <JetDropdownLink as="button"> Log Out </JetDropdownLink>
                    </form>
                  </template>
                </JetDropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-500 transition"
                @click="showingNavigationDropdown = !showingNavigationDropdown"
              >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path
                    :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
          class="sm:hidden"
        >
          <div class="pt-2 pb-3 space-y-1">
            <JetResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
              Dashboard
            </JetResponsiveNavLink>
            <JetResponsiveNavLink :href="route('projects')" :active="route().current('projects')">
              Projects
            </JetResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
              <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                <img
                  class="h-10 w-10 rounded-full object-cover"
                  :src="$page.props.user.profile_photo_url"
                  :alt="$page.props.user.name"
                />
              </div>

              <div>
                <div class="font-medium text-base text-gray-800">
                  {{ $page.props.user.name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                  {{ $page.props.user.email }}
                </div>
              </div>
            </div>

            <div class="mt-3 space-y-1">
              <JetResponsiveNavLink
                :href="route('profile.show')"
                :active="route().current('profile.show')"
              >
                Profile
              </JetResponsiveNavLink>

              <JetResponsiveNavLink
                v-if="$page.props.jetstream.hasApiFeatures"
                :href="route('api-tokens.index')"
                :active="route().current('api-tokens.index')"
              >
                API Tokens
              </JetResponsiveNavLink>

              <!-- Authentication -->
              <form method="POST" @submit.prevent="logout">
                <JetResponsiveNavLink as="button"> Log Out </JetResponsiveNavLink>
              </form>

              <!-- Team Management -->
              <template v-if="$page.props.jetstream.hasTeamFeatures">
                <div class="border-t border-gray-200" />

                <div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>

                <!-- Team Settings -->
                <JetResponsiveNavLink
                  :href="route('teams.show', $page.props.user.current_team)"
                  :active="route().current('teams.show')"
                >
                  Team Settings
                </JetResponsiveNavLink>

                <JetResponsiveNavLink
                  v-if="$page.props.jetstream.canCreateTeams"
                  :href="route('teams.create')"
                  :active="route().current('teams.create')"
                >
                  Create New Team
                </JetResponsiveNavLink>

                <div class="border-t border-gray-200" />

                <!-- Team Switcher -->
                <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

                <template v-for="team in $page.props.user.all_teams" :key="team.id">
                  <form @submit.prevent="switchToTeam(team)">
                    <JetResponsiveNavLink as="button">
                      <div class="flex items-center">
                        <svg
                          v-if="team.id == $page.props.user.current_team_id"
                          class="mr-2 h-5 w-5 text-green-400"
                          fill="none"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>{{ team.name }}</div>
                      </div>
                    </JetResponsiveNavLink>
                  </form>
                </template>
              </template>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header v-if="$slots.header" class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main class="mb-auto min-h-max">
        <slot />
      </main>

      <!-- Page Footer -->
      <footer class="bg-white max-w border-t text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row justify-between items-center gap-4 py-2">
            <nav class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-2 md:gap-6">
              <a
                href="#"
                class="text-gray-500 hover:text-indigo-500 active:text-indigo-600 transition duration-100"
                >About</a
              >
              <a
                href="#"
                class="text-gray-500 hover:text-indigo-500 active:text-indigo-600 transition duration-100"
                >Github</a
              >
              <a
                href="#"
                class="text-gray-500 hover:text-indigo-500 active:text-indigo-600 transition duration-100"
                >Roadmap</a
              >
              <a
                href="#"
                class="text-gray-500 hover:text-indigo-500 active:text-indigo-600 transition duration-100"
                >Twitter</a
              >
            </nav>

            <div>
              <p class="text-gray-500">v{{ $page.props.app_version }}</p>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>
