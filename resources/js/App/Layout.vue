<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
  title: String,
});
</script>

<template>
  <div>
    <Head :title="title" />
    <div class="min-h-screen bg-gray-100">
      <div class="bg-white">
        <div class="mx-auto flex items-center py-4">
          <nav class="w-2/5 pb-2">bla bla</nav>
        </div>
      </div>
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
