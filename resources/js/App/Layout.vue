<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
  title: String,
});
</script>

<template>
  <Head :title="title" />
  <div class="bg-grey-300 font-sans leading-normal tracking-normal">
    <!--Nav-->
    <nav class="bg-gray-800 p-2 mt-0 fixed w-full z-10 top-0">
      <div class="container mx-auto flex flex-wrap items-center">
        <div
          class="
            flex
            w-full
            md:w-1/2
            justify-center
            md:justify-start
            text-white
            font-extrabold
          "
        >
          <a
            class="text-white no-underline hover:text-white hover:no-underline"
            href="https://ramnic.com"
          >
            <span class="text-2xl pl-2"
              ><i class="em em-grinning"></i>RAMNIC</span
            >
          </a>
        </div>
        <div
          class="
            flex
            w-full
            pt-2
            content-center
            justify-between
            md:w-1/2 md:justify-end
          "
        >
          <ul
            class="
              list-reset
              flex
              justify-between
              flex-1
              md:flex-none
              items-center
            "
          >
            <li class="mr-3">
              <Link
                class="inline-block py-2 px-4 text-white no-underline"
                :href="route('secret.create')"
                >Create</Link
              >
            </li>
            <li class="mr-3">
              <Link
                class="
                  inline-block
                  text-gray-600
                  no-underline
                  hover:text-gray-200 hover:text-underline
                  py-2
                  px-4
                "
                href="#"
                >About
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container shadow-lg mx-auto bg-grey-300 mt-24 md:mt-18">
      <slot />
    </main>
  </div>
</template>
