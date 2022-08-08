<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AppButton from "@/App/Button.vue";
import Layout from '@/App/Layout.vue';

const form = useForm({
  secret: null,
});

//const submit = () => {
//  form.post(route("secret.store"));
//};

defineProps({
  secret: String,
});
</script>

<template>
<Layout>
  <form @submit.prevent="submit" class="p-4">
    <div class="flex justify-center">
      <div class="mb-3 xl:w-96">
        <label for="secret" class="form-label inline-block mb-3 text-gray-700"
          >Secret</label
        >

        <textarea
          v-model="secret"
          class="
            form-control
            block
            w-full
            px-2
            py-1.5
            text-base
            font-normal
            text-gray-700
            bg-white bg-clip-padding
            border border-solid border-gray-300
            rounded
            transition
            ease-in-out
            m-0
            focus:text-gray-700
            focus:bg-white
            focus:border-blue-600
            focus:outline-none
          "
          id="secret"
          rows="3"
          placeholder="Your secret"
        ></textarea>
        <AppButton
          class="ml-1 mt-2"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Copy
        </AppButton>
      </div>
    </div>
  </form>
</Layout>
</template>
