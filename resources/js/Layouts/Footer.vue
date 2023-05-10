<template>
  <footer class="bg-white max-w border-t text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between items-center gap-4 py-2">
        <nav class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-2 md:gap-6">
          <p class="text-gray-500">
            Date and Time displayed in local timezone:
            {{ Intl.DateTimeFormat().resolvedOptions().timeZone }}
          </p>

          <!-- <a
            class="text-gray-500 hover:text-primary active:text-primary transition duration-100 cursor-pointer"
            @click="isAboutModalShow = true"
          >
            {{ __('footer.about') }}
          </a> -->
          <!-- <a
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
          </a> -->
        </nav>

        <div class="flex">
          <!-- <LanguageSelector /> -->

          <p class="ml-3 text-gray-500">v{{ $page.props.app_version }} (alpha)</p>
        </div>
      </div>
    </div>
  </footer>
</template>
