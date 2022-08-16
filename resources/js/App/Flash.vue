<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';

const show = ref(true);
const notification = computed(
  () => usePage().props.value.flash?.notification || false
);

// TODO: All usePage().props.value.flash?.message too

watch(usePage().props.flash, async () => {
  show.value = true;
});
</script>

<template>
  <div class="mt-2 mb-2">
    <div v-if="show && notification" class="bg-indigo-500 rounded-full">
      <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between flex-wrap">
          <div class="w-0 flex-1 flex items-center min-w-0">
            <span class="flex p-2 rounded-lg bg-indigo-600">
              <svg
                class="h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </span>

            <p class="ml-3 font-medium text-sm text-white truncate">
              {{ notification }}
            </p>
          </div>
          <div class="shrink-0 sm:ml-3">
            <button
              type="button"
              class="
                -mr-1
                flex
                p-2
                rounded-md
                focus:outline-none
                sm:-mr-2
                transition
                hover:bg-indigo-600
                focus:bg-indigo-600
              "
              aria-label="Dismiss"
              @click.prevent="show = false"
            >
              <svg
                class="h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
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
    </div>
  </div>
</template>
