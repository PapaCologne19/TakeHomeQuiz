<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
  author_name: "",
  biography: "",
  image: null,
});

const submit = () => {
  const data = new FormData();

  data.append('author_name', form.author_name);
  data.append('biography', form.biography);
  data.append('image', form.image);

  form.post(route('author.storeAuthor'), data);
};
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
            <form @submit.prevent="submit" enctype="multipart/form-data">
              <div>
                <InputLabel for="author_name" value="Author Name" />

                <TextInput
                  type="text"
                  id="author_name"
                  class="mt-1 block w-full"
                  v-model="form.author_name"
                  autofocus
                  autocomplete="author_name"
                  required
                />
                <InputError class="mt-2" :message="form.errors.author_name" />
              </div>

              <div class="mt-4">
                <InputLabel for="biography" value="Biography / Description" />

                <TextInput
                  type="text"
                  id="biography"
                  class="mt-1 block w-full"
                  v-model="form.biography"
                  autocomplete="biography"
                  required
                />
                <InputError class="mt-2" :message="form.errors.biography" />
              </div>

              <div class="mt-4">
                <InputLabel for="image" value="image" />

                <TextInput
                  type="file"
                  id="image"
                  class="mt-1 block w-full"
                  @change="form.image = $event.target.files[0]"
                  autocomplete="image"
                  required
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
