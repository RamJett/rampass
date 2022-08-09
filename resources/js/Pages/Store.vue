<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AppButton from '@/App/Button.vue';
import Layout from '@/App/Layout.vue';

const form = useForm({
  secret: '',
});

//const submit = () => {
//  form.post(route("secret.store"));
//};

defineProps({
  url: String,
  expires_at: String,
  views_remaining: Number,
});
</script>

<template>
  <Layout>
    <div class="flex items-center justify-center p-4">
      <form @submit.prevent="submit" class="w-full max-w-lg">
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full px-3">
            <label
              for="url"
              class="
                block
                uppercase
                tracking-wide
                text-gray-700 text-xs
                font-bold
                mb-2
              "
              >Here's the link:</label
            >

            <textarea
              v-model="url"
              readonly="true"
              class="
                appearance-none
                block
                w-full
                bg-gray-200
                text-gray-700
                border border-blue-500
                rounded
                py-3
                px-4
                mb-3
                leading-tight
                focus:outline-none focus:bg-white
              "
              id="url"
              rows="3"
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
    </div>
  </Layout>
</template>
