<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import BreezeButton from "@/Components/Button.vue";
import moment from 'moment';

defineProps({
    title: String,
    country: Object,
});
</script>

<template>
    <Head :title="title" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <div>
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        {{ title }}
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
                        <table class="table-auto w-full">
                            <tbody>
                                <tr>
                                    <td>Id</td>
                                    <td>{{ country.id }}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ country.name }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>
                                        {{
                                            moment(
                                                country.created_at
                                            ).format("DD/MM/YYYY")
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
