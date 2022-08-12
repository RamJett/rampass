<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AppButton from '@/App/Button.vue';
import Layout from '@/App/Layout.vue';
import Label from '@/App/Label.vue';

const useClipboard = (text) => {
  let supported = navigator && 'clipboard' in navigator;

  if (supported) {
    navigator.clipboard.writeText(text);
  }

  return { supported };
};

defineProps({
  url: String,
  expires_at: String,
  views_remaining: Number,
});
</script>

<template>
  <Layout>
    <div class="flex items-center justify-center p-4">
      <div class="w-full max-w-lg">
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full px-3">
            <Label for="url" value="This is the link to secret" class="mb-2" />
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
            <AppButton class="ml-0 mt-2" @click="useClipboard(url)">
              Copy
            </AppButton>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
