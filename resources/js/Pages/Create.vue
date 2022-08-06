<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AppButton from "@/App/Button.vue";

const form = useForm({
  secret: "Hello World!!!",
  time: null,
  views: null,
  units: null,
  time: null,
});

const submit = () => {
  form.post(route("secret.store"));
};

defineProps({
  units: String,
  time: Number,
  views: Number,
});
</script>

<template>
  <form @submit.prevent="submit">
    <label for="secret">Secret:</label>
    <textarea id="secret" v-model="form.secret" />
    <label for="time">Time:</label>
    <input id="time" type="text" v-model="form.time" />
    <label for="units">Units:</label>
    <input id="units" type="text" v-model="form.units" />
    <label for="views">Views:</label>
    <input id="views" type="text" v-model="form.views" />
    <AppButton
      class="ml-1 mt-2"
      :class="{ 'opacity-25': form.processing }"
      :disabled="form.processing"
    >
      submit
    </AppButton>
  </form>
</template>
