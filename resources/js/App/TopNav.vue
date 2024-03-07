<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="bg-blue-200">
    <nav class="flex justify-between items-center">
      <div>
        <Link :href="route('secret.create')">
          <span class="text-xs font-bold uppercase tracking-wide mr-3"
            >RAMPASS</span
          >
        </Link>
      </div>
      <div>
        <Link
          :href="route('secret.create')"
          class="text-xs font-bold uppercase"
        >
          Create
        </Link>
        <Link href="/about" class="text-xs font-bold uppercase ml-3">
          About
        </Link>
      </div>
    </nav>
  </div>
</template>
