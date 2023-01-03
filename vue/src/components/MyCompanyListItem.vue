<template>
    <div
      class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[470px]"
    >

      <h4 class="mt-4 text-lg font-bold">{{ company.name }}</h4>
      <h4 class="text-lg">{{ company.service_offered.name }}</h4>
      <div v-html="company.description" class="overflow-hidden flex-1"></div>
  
      <div class="flex justify-between items-center mt-3">
        <TButton :to="{ name: 'CompanyView', params: { id: company.id } }">
          <PencilIcon class="wo-5 h-5 mr-2 " />
          Edit
        </TButton>
        <div class="flex items-center">
  
          <TButton v-if="company.id" @click="emit('delete', company)" circle link color="red">
            <TrashIcon class="w-5 h-5" />
          </TButton>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import TButton from "./core/TButton.vue";
  import { PencilIcon, ExternalLinkIcon, TrashIcon } from '@heroicons/vue/solid'
  
  const { company } = defineProps({
    company: Object,
  });
  const emit = defineEmits(["delete", "edit"]);
  </script>
  
  <style></style>
  