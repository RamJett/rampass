<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import TopNav from '@/App/TopNav.vue';
import Flash from '@/App/Flash.vue';

defineProps({
  title: String,
});
</script>

<template>
  <Head :title="title" />
  <section>
    <TopNav class="border p-4" />
  </section>
  <header class="max-w-4xl mx-auto mt-10 text-center">
    <Flash />
    <div class="max-w-xl mx-auto">
      <h1 class="text-4xl">
        <slot name="header" />
      </h1>
    </div>
  </header>
  <main>
    <slot name="main" />
  </main>
  <footer>
    <slot name="footer" />
  </footer>
</template>
