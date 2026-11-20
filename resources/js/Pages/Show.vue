<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import AppButton from "@/App/Button.vue";
import Layout from "@/App/Layout.vue";
import Label from "@/App/Label.vue";
import Textarea from "@/App/Textarea.vue";

const props = defineProps({
  uuid: String,
  expires_at: String,
  views_remaining: Number,
});

// The page arrives without the secret; it is fetched (and one view is
// counted) only when the visitor presses "Show secret". Email scanners and
// chat link previews open links but do not press buttons.
const secret = ref("");
const revealed = ref(false);
const gone = ref(false);
const loading = ref(false);
const viewsRemaining = ref(props.views_remaining);
const expiresAt = ref(props.expires_at);

const reveal = async () => {
  loading.value = true;
  try {
    const response = await window.axios.post("/" + props.uuid + "/reveal");
    secret.value = response.data.data.secret;
    viewsRemaining.value = response.data.data.views_remaining;
    expiresAt.value = response.data.data.expires_at;
    revealed.value = true;
  } catch (error) {
    gone.value = true;
  } finally {
    loading.value = false;
  }
};

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
</script>

<template>
  <Layout title="Show Secret">
    <template #main>
      <div class="p-4">
        <div class="flex justify-center">
          <div class="mb-3 xl:w-96">
            <div v-if="gone" class="text-sm font-medium">
              This secret is no longer available.
            </div>

            <div v-else-if="!revealed">
              <p class="mb-3 text-sm font-medium">
                Someone shared a secret with you. Showing it uses one of its
                views.
              </p>
              <AppButton class="ml-0 mt-2" :disabled="loading" @click="reveal">
                Show secret
              </AppButton>
            </div>

            <div v-else>
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
            </div>

            <div
              v-if="!gone"
              class="mt-5 text-balance text-justify text-sm font-medium tracking-tight"
            >
              <p v-if="revealed">
                Copy the text in the box above and save in a safe place. After
                saved, click the delete button to remove from the system.
              </p>
              <p class="mt-5">
                This secret:
              </p>
              <ul class="list-inside list-disc">
                <li>{{ viewsRemaining }} views left.</li>
                <li>Expires "{{ expire_time(expiresAt) }}"</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Layout>
</template>
