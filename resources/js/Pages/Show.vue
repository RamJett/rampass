<script setup>
import { router, Head, Link, useForm } from '@inertiajs/vue3';
import AppButton from '@/App/Button.vue';
import Layout from '@/App/LayoutLight.vue';
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
  router.delete('/' + uuid);
};

const props = defineProps({
  secret: String,
  uuid: String,
});
</script>

<template>
  <Layout title="Show secret">
    <template #main>
      <div class="p-4">
        <div class="flex justify-center">
          <div class="mb-3 xl:w-96">
            <Label for="secret" value="Secret" class="mb-2" />
            <Textarea
              v-model="props.secret"
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
    </template>
  </Layout>
</template>
