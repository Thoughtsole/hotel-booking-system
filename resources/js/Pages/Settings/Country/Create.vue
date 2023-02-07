<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeError from '@/Components/InputError.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { useToast } from "vue-toastification";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    'title': String,
    'token' : String
});
const toast = useToast();
const form = useForm({
    name: '',
    _token: props.token
});

const submit = () => {
    form.post(route('country.store'), {
        onSuccess: () => {
            form.reset();
            toast.success('Country created successfully');
            Inertia.get(route('country.index'));
        },
        onError: (errors) => {
            toast.error('Something went wrong!');
        },
    });
};
</script>

<template>
<Head :title="title" />

<BreezeAuthenticatedLayout>
    <template #header>
        <div class="flex justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{title}}
                </h2>
            </div>
            <div>
                <Link :href="route('country.index')">
                <BreezeButton class="ml-4 float-right">
                    Back
                </BreezeButton>
                </Link>
            </div>
        </div>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <BreezeValidationErrors class="mb-4" />
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-4 gap-2">

                            <div>
                                <BreezeLabel for="country" value="Country Name" />
                                <BreezeInput id="country" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                                <BreezeError :message="form.errors.name" />
                            </div>

                            <div class="col-span-4">

                                <div class="flex items-center justify-end">
                                    <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save
                                    </BreezeButton>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</BreezeAuthenticatedLayout>
</template>
