<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/App/Layout.vue";
import AppButton from "@/App/Button.vue";

const form = useForm({
  secret: null,
  units: props.units_default,
  time: props.time,
  views: props.views,
});

const submit = () => {
  form.post(route("secret.store"));
};

const props = defineProps({
  units: Object,
  units_default: String,
  time: Number,
  views: Number,
});
</script>

<template>
  <Layout title="Create">
    <form @submit.prevent="submit">
      <textarea v-model="form.secret" />

      <input type="number" v-model="form.time" />

      <select v-model="form.units">
        <option v-for="unit in props.units" :value="unit.name" :key="unit.name">
          {{ unit.name }}
        </option>
      </select>

      <input type="number" v-model="form.views" />
      <AppButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        submit
      </AppButton>
    </form>
  </Layout>
</template>
