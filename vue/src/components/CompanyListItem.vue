<template>
  <div class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[470px]">
    <div class="flex justify-between text-sm mb-1">
      <div>Created Date:</div>
      <div>{{ company.created_at }}</div>
    </div>
    <div class="flex justify-between text-sm mb-1">
      <div>Name:</div>
      <div>
        <h4 class="text-lg font-bold">{{ company.name }}</h4>
      </div>
    </div>
    <div class="flex justify-between text-sm mb-1">
      <div>Location:</div>
      <div>{{ company.country.name }}</div>
    </div>

    <div class="flex justify-between text-sm mb-1">
      <div>Service offered:</div>
      <div>{{ company.service_offered.name }}</div>
    </div>


    <div class="flex justify-between text-sm mb-1">
      <div>Description:</div>
      <div v-html="company.description" class="overflow-hidden flex-1"></div>
    </div>
    <div class="flex justify-between items-center mt-3">
          <div>Owner:</div>
          <div class="text-sm font-bold">
            {{ company.owner.first_name }} {{ company.owner.last_name }}
        </div>
    </div>
  </div>
</template>

<script setup>
import TButton from "./core/TButton.vue";

const { company } = defineProps({
  company: Object,
});
const emit = defineEmits(["delete", "edit"]);
</script>

<style>

</style>
