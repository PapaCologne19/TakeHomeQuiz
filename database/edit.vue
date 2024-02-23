<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    'book': Object,
    // 'links': Array
});

// const getImageUrl = (filename) => {
//     return '/storage/images/' + filename; 
// }

const form = useForm({
    book_title: props.book.book_title,
    date_published: props.book.date_published,
    author: props.book.author,
    image: null,
});

// const update = () => {
//     let formData = new FormData();

//     formData.append('book_title', form.book_title);
//     formData.append('date_published', form.date_published);
//     formData.append('author', form.author);
//     formData.append('image', form.image);

//     form.post(route('book.storeBook'), formData);
// };
</script>
<template>
    <Head title="Update Book"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Book</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="update">
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
                                <TextInput
                                    id="author"
                                    type="text"
                                    class="w-full mt-1 block"
                                    v-model="form.author"
                                    required
                                    autocomplete
                                />
                                <InputError class="mt-2" :message="form.errors.author" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="image" value="Image" />
                                <TextInput
                                    id="image"
                                    type="file"
                                    class="w-full mt-1 block"
                                    @change="form.image = $event.target.files[0]"
                                    required
                                    autocomplete="image"
                                />
                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                            <div class="mt-6">
                                <PrimaryButton
                                    class="ms-4" 
                                    :class="{ 'opacity-25': form.processing }" 
                                    :disabled="form.processing"
                                >Update</PrimaryButton>
                            </div>

                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>