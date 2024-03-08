<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppButton from '@/App/Button.vue';
import Layout from '@/App/Layout.vue';
import Label from '@/App/Label.vue';
import Textarea from '@/App/Textarea.vue';

const cancopy = !!navigator.clipboard;
const useClipboard = (text) => {
  let supported = navigator && 'clipboard' in navigator;

  if (supported) {
    navigator.clipboard.writeText(text);
  }

  return { supported };
};

const props = defineProps({
  url: String,
  expires_at: String,
  views_remaining: Number,
});
</script>

<template>
  <Layout title="Saved Secret">
    <template #main>
      <div class="flex items-center justify-center p-4">
        <div class="w-full max-w-lg">
          <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
              <Label
                for="url"
                value="This is the link to secret"
                class="mb-2"
              />
              <Textarea
                v-model="props.url"
                readonly="true"
                class="w-full py-3 px-4 mb-3"
                id="url"
                rows="3"
              />
              <AppButton v-if="cancopy" class="ml-0 mt-2" @click="useClipboard(url)">
                Copy
              </AppButton>
              <div v-else class="ml-0 mt-2">Share link above</div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Layout>
</template>
