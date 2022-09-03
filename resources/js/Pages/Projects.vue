<script setup>
import { Link } from '@inertiajs/inertia-vue3'

import AppLayout from '@/Layouts/AppLayout.vue'
import TableView from '@/Components/Table.vue'

const onRowClick = (row) => {
  window.location = `/process/${row.uuid}`
}
</script>

<template>
  <AppLayout title="Projects">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
    </template>

    <div class="py-12 relative">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="flex flex-col">
            <div class="flex flex-col mt-8">
              <div class="flex-end">
                <Link
                  :href="route('process.create')"
                  class="underline text-sm text-gray-600 hover:text-gray-900"
                >
                  New Process</Link
                >
              </div>

              <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                  class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg"
                >
                  <TableView
                    :rows="$page.props.processes"
                    :columns="[
                      { isKey: true, field: 'id', label: 'ID', width: '10%' },
                      { field: 'name', label: 'Name', width: '65%' },
                      { field: 'created_at', label: 'Created At', width: '25%' },
                    ]"
                    :row-clicked="onRowClick"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
