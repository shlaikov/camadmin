<script setup>
import { ref } from 'vue'

import Modal from '@/Components/Modal.vue'
import JetApplicationLogo from '@/Components/ApplicationLogo.vue'

const isAboutModalShow = ref(false)
</script>

<template>
  <footer class="bg-white max-w border-t text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between items-center gap-4 py-2">
        <nav class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-2 md:gap-6">
          <a
            class="text-gray-500 hover:text-primary active:text-primary transition duration-100 cursor-pointer"
            @click="isAboutModalShow = true"
          >
            {{ __('footer.about') }}
          </a>
          <a
            href="#"
            class="text-gray-500 hover:text-secondary active:text-primary transition duration-100 cursor-pointer"
          >
            {{ __('footer.github') }}
          </a>
          <a
            href="#"
            class="text-gray-500 hover:text-secondary active:text-primary transition duration-100 cursor-pointer"
          >
            {{ __('footer.roadmap') }}
          </a>
          <a
            href="#"
            class="text-gray-500 hover:text-secondary active:text-primary transition duration-100 cursor-pointer"
          >
            {{ __('footer.twitter') }}
          </a>
        </nav>

        <div class="flex">
          <!-- <LanguageSelector /> -->

          <p class="ml-3 text-gray-500">v{{ $page.props.app_version }}</p>
        </div>
      </div>
    </div>

    <Modal :show="isAboutModalShow" @close="emit('close')">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          <div class="mx-auto flex items-center justify-center h-12 rounded-full sm:mx-0 sm:h-10">
            <JetApplicationLogo class="block h-12 w-auto" />
          </div>
        </div>
      </div>

      <div class="px-6 py-4 text-left">
        <p>Camadmin</p>
        <br />
        <p>Version: {{ $page.props.app_version }} ({{ $page.props.latest_hash }})</p>
        <br />

        <div class="w-full">
          <a
            class="flex items-center justify-between p-4 text-sm font-semibold text-white bg-primary hover:bg-secondary rounded-lg shadow-md transition"
            href="https://github.com/shlaikov/camadmin"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                ></path>
              </svg>
              <span>Star this project on GitHub</span>
            </div>
            <span>View more →</span>
          </a>
        </div>
      </div>
    </Modal>
  </footer>
</template>
