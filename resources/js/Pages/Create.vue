<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AppButton from "@/App/Button.vue";

const form = useForm({
  secret: null,
  units: props.units,
  time: props.time,
  views: props.views,
});

const submit = () => {
  form.post(route("secret.store"));
};

const props = defineProps({
  units: String,
  time: Number,
  views: Number,
});
</script>

<template>
  <form @submit.prevent="submit">
    <textarea v-model="form.secret" />

    <input type="text" v-model="form.time" />

    <input type="text" v-model="form.units" />

    <input type="text" v-model="form.views" />
    <AppButton
      :class="{ 'opacity-25': form.processing }"
      :disabled="form.processing"
    >
      submit
    </AppButton>
  </form>
</template>
