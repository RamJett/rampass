<script setup>
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AppButton from '@/App/Button.vue';
import Layout from '@/App/Layout.vue';
import Label from '@/App/Label.vue';
import Textarea from '@/App/Textarea.vue';

const useClipboard = (text) => {
  let supported = navigator && 'clipboard' in navigator;

  if (supported) {
    navigator.clipboard.writeText(text);
  }

  return { supported };
};

const destroy = (uuid) => {
  Inertia.delete('/' + uuid);
};

defineProps({
  secret: String,
  uuid: String,
});
</script>

<template>
  <Layout>
    <div class="p-4">
      <div class="flex justify-center">
        <div class="mb-3 xl:w-96">
          <Label for="secret" value="Secret" class="mb-2" />
          <Textarea
            v-model="secret"
            readonly="true"
            class="w-full py-3 px-4 mb-3"
            id="secret"
            rows="3"
            placeholder="Your secret"
          />
          <div class="flex justify-between">
            <AppButton class="ml-0 mt-2" @click="useClipboard(secret)">
              Copy
            </AppButton>
            <AppButton class="ml-0 mt-2" @click="destroy(uuid)">
              Delete
            </AppButton>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
