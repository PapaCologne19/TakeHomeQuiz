<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import { Head, Link } from "@inertiajs/vue3";
import { defineProps, ref, computed } from "vue";

const props = defineProps({
  books: Array,
  authors: Array,
});

const getImageUrl = (filename) => {
  return "/storage/images/" + filename;
};

const searchQuery = ref("");

const filteredBooks = computed(() => {
  return props.books.filter((book) =>
    book.book_title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
</script>

<template>
  <Head title="Book" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Book</h2>
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
            <div class="container py-5 flex justify-start">
              <Link
                :href="route('book.addBook')"
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
                    <th class="px-6 py-3">Book ID</th>
                    <th class="px-6 py-3">Book Title</th>
                    <th class="px-6 py-3">Date Published</th>
                    <th class="px-6 py-3">Author</th>
                    <th class="px-6 py-3">Image</th>
                    <th class="px-6 py-3">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="book in filteredBooks"
                    :key="book.id"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                  >
                    <td class="px-6 py-4">{{ book.id }}</td>
                    <td class="px-6 py-4">{{ book.book_title }}</td>
                    <td class="px-6 py-4">{{ book.date_published }}</td>
                    <td class="px-6 py-4">
                      <template v-for="(author, index) in book.authors" :key="author.id">
                        <span v-if="index !== 0" class="mx-1">, </span
                        >{{ author.author_name }}
                      </template>
                    </td>

                    <td class="px-6 py-4">
                      <template v-for="(image) in book.images" :key="image.id">
                        <img
                          :src="getImageUrl(image.filename)"
                          alt="Book Image"
                          class="w-24 h-24 object-cover rounded-lg"
                        />
                      </template>
                    </td>
                    <td>
                      <Link
                        :href="`/books/${book.id}/edit`"
                        class="text-white bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:bg-gradient-to-br focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                      >
                        Edit</Link
                      >
                      <Link
                        :href="`/books/${book.id}/delete`"
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
