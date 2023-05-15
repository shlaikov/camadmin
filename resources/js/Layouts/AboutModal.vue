<script setup>
import { computed } from 'vue'

import JetApplicationLogo from '@/Components/ApplicationLogo.vue'
import JetDialogModal from '@/Components/DialogModal.vue'
import JetSecondaryButton from '@/Components/SecondaryButton.vue'
import { useAppStore } from '@/Stores/app'

const appStore = useAppStore()

const isAboutModalOpen = computed(() => appStore.isAboutModalOpen)

const closeModal = () => {
  appStore.$patch((state) => {
    state.isAboutModalOpen = false
  })
}
</script>

<template>
  <JetDialogModal :show="isAboutModalOpen" @close="closeModal">
    <template #content>
      <div class="sm:flex sm:items-start">
        <div class="mx-auto flex items-center justify-center h-12 rounded-full sm:mx-0 sm:h-10">
          <JetApplicationLogo class="block h-12 w-auto" />
        </div>
      </div>

      <div class="py-4 text-left">
        <br />
        <p>Version: {{ $page.props.app_version }} ({{ $page.props.latest_hash }})</p>
        <br />
        <p>
          Copyright © 2023
          <a
            class="text-gray-500 hover:text-primary active:text-primary transition duration-100 cursor-pointer"
            href="https://shlaikov.github.io/"
            target="_blank"
            >Alexey Shlaikov</a
          >
        </p>
        <p>
          Camadmin is distributed under
          <a
            class="text-gray-500 hover:text-primary active:text-primary transition duration-100 cursor-pointer"
            href="https://github.com/shlaikov/camadmin/blob/main/LICENSE.md"
            target="_blank"
          >
            MIT Licence</a
          >
        </p>
        <br />
        For comments or questions you can
        <a
          class="text-gray-500 hover:text-primary active:text-primary transition duration-100 cursor-pointer"
          href="https://github.com/shlaikov/camadmin/discussions"
          target="_blank"
          >go to the support page</a
        >
        or
        <a
          class="text-gray-500 hover:text-primary active:text-primary transition duration-100 cursor-pointer"
          href="mailto:alexey.shlaikov@camadmin.dev"
          >send me an email</a
        >.<br />If you find a bug, please report it on
        <a
          class="text-gray-500 hover:text-primary active:text-primary transition duration-100 cursor-pointer"
          href="https://github.com/shlaikov/camadmin/issues"
          target="_blank"
          >this page</a
        >.
        <br />

        <div class="w-full mt-6">
          <a
            class="flex items-center justify-between p-4 text-sm font-semibold text-white bg-primary hover:bg-secondary rounded-lg shadow-md transition"
            href="https://github.com/shlaikov/camadmin"
            target="_blank"
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
    </template>

    <template #footer>
      <JetSecondaryButton @click="closeModal"> Close </JetSecondaryButton>
    </template>
  </JetDialogModal>
</template>
