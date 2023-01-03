<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ route.params.id ? model.title : "Create a Company" }}
        </h1>

        <div class="flex">
          <TButton v-if="model.slug" link :href="`/view/company/${model.slug}`" target="_blank" class="mr-2">
            <ExternalLinkIcon class="w-5 h-5" />
            View Public link
          </TButton>
          <TButton v-if="route.params.id" color="red" @click="deleteCompany()">
            <TrashIcon class="w-5 h-5 mr-2" />
            Delete
          </TButton>
        </div>
      </div>
    </template>
    <div v-if="companyLoading" class="flex justify-center">Loading...</div>
    <form v-else @submit.prevent="saveCompany" class="animate-fade-in-down">
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <!-- Company Fields -->
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

          <!-- Name -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="title" id="title" v-model="model.name" autocomplete="company_title"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
          </div>
          <!--/ Name -->

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" v-model="model.email" autocomplete="company_email"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
          </div>
          <!--/ Email -->

          <!-- phone_number -->
          <div>
            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="tel" name="phone_number" id="phone_number" v-model="model.phone_number"
              autocomplete="company_phone_number"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
          </div>
          <!--/ phone_number -->
          <!-- Email -->
          <div>
            <label for="service" class="block text-sm font-medium text-gray-700">Service {{ model.service_offered ? '('+model.service_offered.name+')' : '' }}</label>
            <input type="text" name="service" id="service" :v-model="model.service" 
              autocomplete="company_service"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
          </div>
          <!--/ Email -->
          <div>
            <label for="country_id" class="block text-sm font-medium text-gray-700">Country {{ model.country ? '('+model.country.name+')' : '' }}</label>
            <select v-model="model.country_id" name="country_id"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
          <option disabled value="">Please Select</option>
          <option v-for="option in countries.data" :key="option.id" :value="option.id">
            {{ option.name }}
          </option>
        </select>
          </div>
          <!-- Description -->
          <div>
            <label for="about" class="block text-sm font-medium text-gray-700">
              Description
            </label>
            <div class="mt-1">
              <textarea id="description" name="description" rows="3" v-model="model.description"
                autocomplete="company_description"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                placeholder="Describe your company" />
            </div>
          </div>
          <!-- Description -->
        </div>
        <!--/ Company Fields -->


        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
          <TButton>
            <SaveIcon class="w-5 h-5 mr-2" />
            Save
          </TButton>
        </div>
      </div>
    </form>
  </PageComponent>
</template>

<script setup>
import { v4 as uuidv4 } from "uuid";
import { computed, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { SaveIcon, TrashIcon, ExternalLinkIcon } from '@heroicons/vue/solid'
import store from "../store";
import PageComponent from "../components/PageComponent.vue";
import TButton from "../components/core/TButton.vue";

const router = useRouter();

const route = useRoute();

// Get company loading state, which only changes when we fetch company from backend
const companyLoading = computed(() => store.state.currentCompany.loading);

// Create empty company
let model = ref({
  name: "",
  country_id: "",
  company_id: "",
  description: null,
});
const countries = computed(() => store.state.countries);

store.dispatch("getCountries");

// Watch to current company data change and when this happens we update local model
watch(
  () => store.state.currentCompany.data,
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal)),
      status: !!newVal.status,
    };
  }
);

// If the current component is rendered on company update route we make a request to fetch company
if (route.params.id) {
  const curCompany = computed(() => store.state.currentCompany);

  store.dispatch("getCompany", route.params.id);
}


/**
 * Create or update company
 */
function saveCompany() {
  let action = "created";
  if (model.value.id) {
    action = "updated";
  }
  store.dispatch("saveCompany", { ...model.value }).then(({ data }) => {
    store.commit("notify", {
      type: "success",
      message: "The company was successfully " + action,
    });
    router.push({
      name: "CompanyView",
      params: { id: data.data.id },
    });
  });
}

function deleteCompany() {
  if (
    confirm(
      `Are you sure you want to delete this company? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deleteCompany", model.value.id).then(() => {
      router.push({
        name: "Companies",
      });
    });
  }
}
</script>

<style>

</style>
