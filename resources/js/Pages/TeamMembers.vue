<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden border border-gray-200 shadow-sm sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
              {{ $page.props.user.current_team.name }}
            </h3>
          </div>
          <div class="border-t border-gray-200">
            <section>
              <div class="container py-4 mx-auto">
                <div
                  v-for="member in $page.props.team_members"
                  :key="member.id"
                  class="flex flex-wrap -m-2 px-6"
                >
                  <div class="p-2 lg:w-1/3 md:w-1/2 w-full">
                    <div class="h-full flex items-center border-gray-200 border p-4 rounded-lg">
                      <img
                        :alt="member.name"
                        class="w-16 h-16 bg-gray-100 object-cover object-center flex-shrink-0 rounded-full mr-4"
                        :src="member.profile_photo_url"
                      />
                      <div class="flex-grow">
                        <h2 class="text-gray-900 title-font font-medium">{{ member.name }}</h2>
                        <p class="text-gray-500">{{ member.email }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
