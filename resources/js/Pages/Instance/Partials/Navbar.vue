<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  instanceId: {
    required: true,
    type: String,
  },
})
</script>

<template>
  <ul class="flex font-medium flex-row space-x-8">
    <li>
      <Link
        :href="route('instances.show', { id: instanceId })"
        class="py-5 h-full text-gray-500 hover:text-gray-700 transition"
        :class="{
          'text-gray-900 hover:text-gray-500 border-primary border-b-2 hover:border-secondary':
            route().current('instances.show'),
        }"
        :aria-current="route().current('instances.show') ? 'page' : ''"
      >
        Processes
      </Link>
    </li>
    <li>
      <Link
        :href="route('instances.desicions', { id: instanceId })"
        class="py-5 h-full text-gray-500 hover:text-gray-700 transition"
        :class="{
          'text-gray-900 hover:text-gray-500 border-primary border-b-2 hover:border-secondary':
            route().current('instances.desicions'),
        }"
        :aria-current="route().current('instances.desicions') ? 'page' : ''"
      >
        Desicions
      </Link>
    </li>
    <li>
      <Link
        :href="route('instances.incidents', { id: instanceId })"
        class="py-5 h-full text-gray-500 hover:text-gray-700 transition"
        :class="{
          'text-gray-900 hover:text-gray-500 border-primary border-b-2 hover:border-secondary':
            route().current('instances.incidents'),
        }"
        :aria-current="route().current('instances.incidents') ? 'page' : ''"
      >
        Incidents
      </Link>
    </li>
  </ul>
</template>
