<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
  authors: Array,
});

const successMessage = ref(props.success);

const getImageUrl = (filename) => {
  return "/storage/images/" + filename;
};

const searchQuery = ref("");

const filteredAuthors = computed(() => {
  return props.authors.filter((author) =>
    author.author_name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
</script>

<template>
  <Head title="Author" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Author</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div v-if="$page.props.flash.success" class="text-green-400">
              {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash.error" class="text-red-500">
              {{ $page.props.flash.error }}
            </div>
            <div class="container p-5 flex justify-start">
              <Link
                :href="route('author.addAuthor')"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
              >
                Add</Link
              >
            </div>

            <div class="relative overflow-x-auto">
              <div class="flex justify-end p-5">
                <input
                  type="text"
                  class="block p-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  v-model="searchQuery"
                  placeholder="Search..."
                />
              </div>
              <table
                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
              >
                <thead>
                  <tr
                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                  >
                    <th class="px-6 py-3">Author ID</th>
                    <th class="px-6 py-3">Author Name</th>
                    <th class="px-6 py-3">Biography</th>
                    <th class="px-6 py-3">Image</th>
                    <th class="px-6 py-3">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="author in filteredAuthors" :key="author.id">
                    <td class="px-6 py-4">{{ author.id }}</td>
                    <td class="px-6 py-4">{{ author.author_name }}</td>
                    <td class="px-6 py-4">{{ author.biography }}</td>
                    <td class="px-6 py-4">
                      <div v-if="author.images.length > 0">
                        <img
                          v-for="(image, index) in author.images"
                          :key="index"
                          :src="getImageUrl(image.filename)"
                          alt="Author Image"
                          class="w-30 h-24 object-cover rounded-lg"
                        />
                      </div>
                      <div v-else>No Image</div>
                    </td>
                    <td class="px-6 py-4">
                      <Link
                        :href="`/authors/${author.id}/edit`"
                        class="text-white bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:bg-gradient-to-br focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                      >
                        Edit
                      </Link>
                      <Link
                        :href="`/authors/${author.id}/delete`"
                        class="text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 font-medium rounded-lg text-sm px-3 py-2 mt-3 text-center me-2"
                        method="delete"
                        as="button"
                        type="button"
                        >Delete
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
