<script setup>
import { router, Head, Link, useForm } from "@inertiajs/vue3";
import AppButton from "@/App/Button.vue";
import Layout from "@/App/Layout.vue";
import Label from "@/App/Label.vue";
import Textarea from "@/App/Textarea.vue";

const cancopy = !!navigator.clipboard;
const useClipboard = (text) => {
  let supported = navigator && "clipboard" in navigator;

  if (supported) {
    navigator.clipboard.writeText(text);
  }

  return { supported };
};

const destroy = (uuid) => {
  router.delete("/" + uuid);
};

const expire_time = (Time) => {
  let date = new Date(Date.parse(Time + " UTC"));

  return date.toLocaleString();
};

const props = defineProps({
  secret: String,
  uuid: String,
});
</script>

<template>
  <Layout title="Show Secret">
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
              <AppButton
                v-if="cancopy"
                class="ml-0 mt-2"
                @click="useClipboard(secret)"
              >
                Copy
              </AppButton>
              <AppButton class="ml-0 mt-2" @click="destroy(uuid)">
                Delete
              </AppButton>
            </div>

            <div
              class="mt-5 text-balance text-justify text-sm font-medium tracking-tight"
            >
              <p>
                Copy the text in the box above and save in a safe place. After
                saved, click the delete button to remove from the system.
              </p>
              <p class="mt-5">
                This secret:
              </p>
              <ul class="list-inside list-disc">
                <li>{{ $page.props.views_remaining }} views left.</li>
                <li>Expires "{{ expire_time($page.props.expires_at) }}"</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Layout>
</template>
