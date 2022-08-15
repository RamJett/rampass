<script setup>
defineProps({
  value: String,
});
</script>

<template>
  <label class="text-xs font-bold uppercase tracking-wide">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>
  </label>
</template>
