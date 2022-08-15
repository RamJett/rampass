<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
  <nav class="flex justify-between items-center">
    <div>
      <Link href="#">
        <span class="text-xs font-bold uppercase mr-3">RAMPASS</span>
      </Link>
    </div>
    <div>
      <Link href="/" class="text-xs font-bold uppercase">Create</Link>
      <Link href="/about" class="text-xs font-bold uppercase ml-3">About</Link>
    </div>
  </nav>
</template>
