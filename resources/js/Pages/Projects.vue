<script setup>
import { Link } from '@inertiajs/inertia-vue3'

import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import DropZone from '@/Components/DropZone.vue'
import FilePreview from '@/Components/FilePreview.vue'
import createUploader from '@/Compositions/file-uploader'
import useFileList from '@/Compositions/file-list'
import Process from '@/Components/Process.vue'
import NoData from '@/Components/Icons/NoData.vue'

defineProps({
  processes: Object,
})

const { files, addFiles, removeFile } = useFileList()

const onInputChange = (e) => {
  addFiles(e.target.files)

  e.target.value = null // reset so that selecting the same file again will still cause it to fire this change
}

const { uploadFiles } = createUploader(route('process.import'))
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
              <div class="mb-4 px-4 flex-end">
                <Link
                  v-show="!files.length"
                  :href="route('process.create')"
                  class="inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-md"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                  </svg>
                  New Process</Link
                >
                <button
                  v-show="files.length"
                  class="ml-4 inline-flex text-gray-700 bg-gray-100 border-0 py-2 px-6 focus:outline-none hover:bg-gray-200 rounded text-md"
                  @click.prevent="uploadFiles(files)"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6 mr-2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"
                    />
                  </svg>

                  Upload
                </button>
              </div>

              <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <DropZone v-slot="{ dropZoneActive }" class="drop-area" @files-dropped="addFiles">
                  <div
                    :class="{
                      'border-2 border-black-300 border-dashed mx-4 my-4 rounded-lg opacity-50':
                        dropZoneActive,
                    }"
                  >
                    <div class="mx-auto px-2 flex flex-wrap min-w-full align-middle">
                      <div
                        v-if="files.length === 0 && processes.data.length === 0"
                        class="w-full mb-4 mt-6 px-4 text-gray-600 body-font"
                      >
                        <div class="w-48 mx-auto my-4 items-center justify-center text-center">
                          <NoData />
                          <p class="mt-8 mb-8 leading-relaxed">
                            <span class="mb-4 font-bold text-gray-900">No data</span><br />
                            Create or import your business process
                          </p>
                        </div>
                      </div>
                      <div
                        v-for="process in processes.data"
                        v-else
                        :key="process.id"
                        class="lg:w-1/4 md:w-1/2 sm:w-1/1 w-full p-2"
                      >
                        <Process :process="process" />
                      </div>
                    </div>

                    <Pagination class="mb-4 mt-6 px-4" :links="processes.links" />
                  </div>
                  <div v-show="files.length > 0" class="py-2 -my-2 pb-6">
                    <div class="mx-4 mb-4 mt-6 px-4">
                      <input
                        id="file_input"
                        class="hidden w-full px-2 py-6 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none"
                        aria-describedby="file_input_help"
                        type="file"
                        multiple
                        @change="onInputChange"
                      />
                      <p class="mt-1 text-sm text-gray-500">BPMN (MAX. 400MB).</p>
                    </div>
                    <ul class="image-list">
                      <FilePreview
                        v-for="file of files"
                        :key="file.id"
                        :file="file"
                        tag="li"
                        @remove="removeFile"
                      />
                    </ul>
                  </div>
                </DropZone>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
