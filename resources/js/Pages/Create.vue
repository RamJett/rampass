<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import Layout from '@/App/Layout.vue';
import AppButton from '@/App/Button.vue';
import Label from '@/App/Label.vue';

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
  time: Number,
  views: Number,
});
</script>

<template>
  <Layout title="Create">
    <div class="flex items-center justify-center p-4">
      <form @submit.prevent="submit" class="w-full max-w-lg">
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full px-3">
            <Label for="secret" value="Secret" class="mb-2" />
            <textarea
              v-model="form.secret"
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
              placeholder="Type secret here"
            />
          </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
            <Label for="time" value="Time" class="mb-2" />
            <input
              v-model="form.time"
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
              id="time"
              type="number"
              min="1"
            />
          </div>
          <div class="w-full md:w-1/2 px-3">
            <Label for="units" value="Units" class="mb-2" />
            <select
              v-model="form.units"
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
              id="units"
            >
              <option
                v-for="unit in props.units"
                :value="unit.name"
                :key="unit.name"
              >
                {{ unit.name }}
              </option>
            </select>
          </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full px-3">
            <Label for="views" value="Views" class="mb-2" />

            <input
              type="number"
              v-model="form.views"
              id="views"
              min="1"
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
                focus:outline-none focus:bg-white focus:border-gray-500
              "
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
