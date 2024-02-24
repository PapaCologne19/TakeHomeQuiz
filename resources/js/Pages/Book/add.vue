<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
  authors: Array,
});

const form = useForm({
  book_title: "",
  date_published: "",
  author_id: "",
  image: null,
});

const storeBook = () => {
  form.post(route("book.storeBook"));
};
</script>
<template>
  <Head title="Add Book" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Book</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="storeBook" enctype="multipart/form-data">
              <div>
                <InputLabel for="book_title" value="Book Title" />
                <TextInput
                  id="book_title"
                  type="text"
                  class="w-full mt-1 block"
                  v-model="form.book_title"
                  required
                  autocomplete
                  autofocus
                />

                <InputError class="mt-2" :message="form.errors.book_title" />
              </div>

              <div class="mt-4">
                <InputLabel for="date_published" value="Date Published" />
                <TextInput
                  id="date_published"
                  type="date"
                  class="w-full mt-1 block"
                  v-model="form.date_published"
                  required
                  autocomplete
                />
                <InputError class="mt-2" :message="form.errors.date_published" />
              </div>

              <div class="mt-4">
                <InputLabel for="author" value="Author" />
                <select
                  id="author"
                  class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  required
                  v-model="form.author_id"
                >
                  <option value="" selected disabled>Select Author</option>
                  <option v-for="author in authors" :key="author.id" :value="author.id">
                    {{ author.author_name }}
                  </option>
                </select>
              </div>

              <div class="mt-4">
                <InputLabel for="image" value="Image" />
                <TextInput
                  id="image"
                  type="file"
                  class="w-full mt-1 block pt-5"
                  @input="form.image = $event.target.files[0]"
                  autocomplete="image"
                />
                <InputError class="mt-2" :message="form.errors.image" />
              </div>

              <div class="mt-6">
                <PrimaryButton
                  class="ms-4"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                  >Add</PrimaryButton
                >
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
