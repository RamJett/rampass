<script setup>
defineProps({
  value: String,
});
</script>

<template>
  <label class="block uppercase tracking-wide font-bold text-gray-700">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>
  </label>
</template>
