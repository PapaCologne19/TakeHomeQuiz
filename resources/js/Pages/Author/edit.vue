<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";

const props = defineProps({
  authors: Object,
  links: Object,
});

const getImageUrl = (filename) => {
  return "/storage/images/" + filename;
};

const images = props.authors.images;
const form = useForm({
  author_name: props.authors.author_name,
  biography: props.authors.biography,
  image: null,
});

const handleImageChange = (event) => {
  form.image = event.target.files[0];
};

const update = () => {
  router.post(`/authors/${props.authors.id}/update`, {
    _method: "put",
    author_name: form.author_name,
    biography: form.biography,
    image: form.image,
  });
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
            <form @submit.prevent="update">
              <div>
                <InputLabel for="author_name" value="Author Name" />
                <TextInput
                  id="author_name"
                  type="text"
                  class="w-full mt-1 block"
                  v-model="form.author_name"
                  autofocus
                  autocomplete="author_name"
                  required
                />
                <InputError class="mt-2" :message="form.errors.author_name" />
              </div>

              <div class="mt-4">
                <InputLabel for="biography" value="Biography" />
                <TextInput
                  id="biography"
                  type="text"
                  class="w-full mt-1 block"
                  v-model="form.biography"
                  autocomplete="biography"
                  required
                />
                <InputError class="mt-2" :message="form.errors.biography" />
              </div>

              <div class="mt-4">
                <InputLabel for="image" value="Image" />

                <div v-if="images && images.length > 0">
                  <div v-for="(image, index) in images" :key="index">
                    <img
                      :src="getImageUrl(image.filename)"
                      alt="Book Image"
                      class="w-24 h-24 object-cover rounded-lg"
                    />
                  </div>
                </div>
                <div v-else>No Image</div>

                <TextInput
                  id="image"
                  type="file"
                  class="w-full mt-1 block"
                  @change="handleImageChange"
                  autocomplete="image"
                />
                <InputError class="mt-2" :message="form.errors.image" />
              </div>

              <div class="mt-6">
                <PrimaryButton
                  class="ms-4"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                  >Update</PrimaryButton
                >
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
