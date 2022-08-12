<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import Layout from '@/App/Layout.vue';
import AppButton from '@/App/Button.vue';
import Label from '@/App/Label.vue';
import Input from '@/App/Input.vue';
import Textarea from '@/App/Textarea.vue';
import Select from '@/App/Select.vue';

const form = useForm({
  secret: null,
  units: props.units_default,
  time: props.time,
  views: props.views,
});

const submit = () => {
  form.post(route('secret.store'));
};

const props = defineProps({
  units: Array,
  units_default: String,
  time: String,
  views: String,
});
</script>

<template>
  <Layout title="Create">
    <div class="flex items-center justify-center p-4">
      <form @submit.prevent="submit" class="w-full max-w-lg">
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full px-3">
            <Label for="secret" value="Secret" class="mb-2" />
            <Textarea
              v-model="form.secret"
              id="secret"
              placeholder="Type secret here"
              class="w-full py-3 px-4 mb-3"
            />
          </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
            <Label for="time" value="Time" class="mb-2" />
            <Input
              v-model="form.time"
              type="number"
              min="1"
              id="time"
              class="py-3 px-4 mb-3"
              required
            />
          </div>
          <div class="w-full md:w-1/2 px-3">
            <Label for="units" value="Units" class="mb-2" />
            <Select
              v-model="form.units"
              id="units"
              class="w-full py-3 px-4 mb-3"
            >
              <option
                v-for="unit in props.units"
                :value="unit.name"
                :key="unit.name"
              >
                {{ unit.name }}
              </option>
            </Select>
          </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full px-3">
            <Label for="views" value="Views" class="mb-2" />
            <Input
              v-model="form.views"
              id="views"
              type="number"
              min="1"
              class="w-full py-3 px-4 mb-3"
              required
            />
          </div>
        </div>
        <AppButton
          class="ml-0"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Create
        </AppButton>
      </form>
    </div>
  </Layout>
</template>
