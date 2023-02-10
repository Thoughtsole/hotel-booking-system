<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import BreezeError from "@/Components/InputError.vue";
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { useToast } from "vue-toastification";
import { Inertia } from "@inertiajs/inertia";
import Multiselect from "@vueform/multiselect";
import axios from "axios";

const props = defineProps({
    title: String,
    token: String,
});

const toast = useToast();
const form = useForm({
    name: "",
    country_id: null,
    _token: props.token,
});

const submit = () => {
    form.post(route("city.store"), {
        onSuccess: () => {
            form.reset();
            toast.success("city created successfully");
            Inertia.get(route("city.index"));
        },
        onError: (errors) => {
            toast.error("Something went wrong!");
        },
    });
};

const fetchCountries = async (q) => {
    const response = await axios.get(route("country.index"), {
        params: {
            fetch: true,
            cols: ["id", "name"],
            filter: JSON.stringify({
                name: q,
            }),
        },
    });
    return response.data;
    return window._.map(response.data, function (val) {
        return { value: val.id, label: val.name };
    });
};
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
                        <BreezeValidationErrors class="mb-4" />
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-4 gap-2">
                                <div>
                                    <BreezeLabel for="city" value="City Name" />
                                    <BreezeInput
                                        id="city"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        autocomplete="name"
                                    />
                                    <BreezeError :message="form.errors.name" />
                                </div>

                                <div>
                                    <BreezeLabel
                                        for="country_id"
                                        value="Country Name"
                                    />
                                    <Multiselect
                                        id="country_id"
                                        v-model="form.country_id"
                                        mode="single"
                                        placeholder="Choose your City"
                                        :close-on-select="true"
                                        :filter-results="false"
                                        :min-chars="0"
                                        :resolve-on-load="false"
                                        :infinite="true"
                                        :limit="10"
                                        :clear-on-search="false"
                                        :delay="0"
                                        :searchable="true"
                                        label="name"
                                        valueProp="id"
                                        :options="
                                            async (query) => {
                                                return await fetchCountries(
                                                    query
                                                );
                                            }
                                        "
                                        @open="
                                            (select$) => {
                                                if (select$.noOptions) {
                                                    select$.resolveOptions();
                                                }
                                            }
                                        "
                                    />
                                </div>

                                <div class="col-span-4">
                                    <div class="flex items-center justify-end">
                                        <BreezeButton
                                            class="ml-4"
                                            :class="{
                                                'opacity-25': form.processing,
                                            }"
                                            :disabled="form.processing"
                                        >
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
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
.multiselect {
    margin-top: 4px;
}
</style>
