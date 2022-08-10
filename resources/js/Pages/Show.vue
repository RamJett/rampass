<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AppButton from '@/App/Button.vue';
import Layout from '@/App/Layout.vue';

const useClipboard = (text) => {
  let supported = navigator && 'clipboard' in navigator;

  if (supported) {
    navigator.clipboard.writeText(text);
  }

  return { supported };
};

defineProps({
  secret: String,
});
</script>

<template>
  <Layout>
    <div class="p-4">
      <div class="flex justify-center">
        <div class="mb-3 xl:w-96">
          <label
            for="secret"
            class="
              block
              uppercase
              tracking-wide
              text-gray-700 text-xs
              font-bold
              mb-2
            "
            >Secret</label
          >

          <textarea
            v-model="secret"
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
            id="secret"
            rows="3"
            placeholder="Your secret"
          ></textarea>
          <AppButton class="ml-0 mt-2" @click="useClipboard(secret)">
            Copy
          </AppButton>
        </div>
      </div>
    </div>
  </Layout>
</template>
