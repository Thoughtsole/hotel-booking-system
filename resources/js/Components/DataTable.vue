<script setup>
import {
    Link
} from '@inertiajs/inertia-vue3'
import {
    ref,
    computed,
getCurrentInstance
} from 'vue';
import moment from 'moment';

const app = getCurrentInstance();

const props = defineProps({
    'displayedColumns': Object,
    'dataSource': Object,
    'orderBy': String,
    'orderField': String
});

const emit = defineEmits(['sortingOrder', 'multiAction'])

const selected = ref([]);
const selectAll = computed({
    get: () => {
        emit('multiAction', selected.value);
        return props.dataSource ? props.dataSource.length == selected.value.length : false;
    },
    set: (val) => {
        var temp = [];
        if (val) {
            props.dataSource.forEach(function (d) {
                temp.push(d.id);
            });
        }
        emit('multiAction', selected.value = temp);
    }
});

function getRowVal(obj, key) {
    return _.get(obj, key, '...');
}

function changeOrder(field, order) {
    emit('sortingOrder', {
        orderField: field,
        orderBy: order == 'asc' ? 'desc' : 'asc'
    });
}

function getFormattedDate(value) {

    return value ? moment(value).format('DD-MM-YYYY') : '...';
}

function getActionTitle(action) {
    return _.replace(action, '_', ' ');
}

// function openModal(user){
//     app.appContext.config.globalProperties.eventBus.emit('openModal', {
//         user: user
//     });
// }
</script>

<template>
<table class="w-full text-sm text-left text-gray-100 dark:text-gray-900">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-200 dark:text-gray-900">
        <tr>
            <template v-for="col in displayedColumns" :key="col.ref">
                <th v-if="col.ref =='select'" scope="col" class="px-6 py-3">
                    <input type="checkbox" v-model="selectAll">
                </th>
                <th v-if="(['select','action']).indexOf(col.ref) == -1" scope="col" class="px-6 py-3">
                    {{col.name}}
                    <template v-if="orderField == col.ref">
                        <span class="arrow asc desc" :class="[(orderBy == 'desc') ? 'active-desc' : 'active-asc']" @click="changeOrder(orderField, orderBy)"></span>
                    </template>
                    <template v-else>
                        <span class="arrow desc asc" @click="changeOrder(col.ref, 'desc')"></span>
                    </template>
                </th>
                <th v-if="col.ref =='action'" scope="col" class="px-6 py-3">
                    Action
                </th>
            </template>
        </tr>
    </thead>
    <tbody>
        <template v-if="dataSource.length">
            <template v-for="row in dataSource" :key="row.id">
                <tr class="bg-white border-b dark:bg-gray-100 dark:border-gray-100 hover:bg-gray-50 dark:hover:bg-gray-400">
                    <template v-for="filter_col in displayedColumns">
                        <td v-if="filter_col.ref == 'select'" class="px-6 py-4">
                            <input type="checkbox" v-model="selected" :value="row.id">
                        </td>
                        <td v-if="(['select','action']).indexOf(filter_col.ref) == -1" class="px-6 py-4">
                            <template v-if="filter_col.data_type == 'date'">
                                {{ getFormattedDate(getRowVal(row, filter_col.ref)) }}
                            </template>
                            <template v-else>
                                {{ getRowVal(row, filter_col.ref) }}
                            </template>
                        </td>
                        <td v-if="filter_col.ref == 'action'" class="px-6 py-4">
                            <div v-if="filter_col.actions.length > 0" class="flex space-x-4 text-right">
                                <template v-for="action in filter_col.actions">
                                <template v-if="action.type == 'link'">
                                    <Link :href="route(action.route_name, row.id)" :title="getActionTitle(action.name)">
                                        <svg class="w-6 h-6 stroke-black hover:stroke-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path :d="action.icon ? action.icon : 'M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z'" />
                                        </svg>
                                    </Link>
                                </template>
                                <!-- <template v-else-if="action.type == 'modal'">
                                    <button @click="openModal(row)">
                                        <svg class="w-6 h-6 stroke-black hover:stroke-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path :d="action.icon ? action.icon : 'M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z'" />
                                        </svg>
                                    </button>
                                </template>
                                <template v-else="true">
                                </template> -->
                                </template>
                            </div>
                        </td>
                    </template>
                </tr>
            </template>
        </template>
        <template v-else>
            <tr class="bg-white border-b dark:bg-gray-100 dark:border-gray-100 hover:bg-gray-50 dark:hover:bg-gray-400">
            <td :colspan="displayedColumns.length" class="text-center text-orange-400 p-4">No Data</td>
            </tr>
        </template>
    </tbody>
</table>
</template>

<style scoped>
.arrow {
    display: inline-block;
    vertical-align: middle;
    width: 0;
    height: 0;
    margin-left: 5px;
    opacity: 0.66;
    margin-bottom: 1px;
}

.arrow.asc {
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-bottom: 4px solid var(--primary-color);
}

.arrow.desc {
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 4px solid var(--primary-color);
}
.arrow.active-asc{
    border-bottom: 4px solid rgb(129 140 248);
}
.arrow.active-desc{
    border-top: 4px solid rgb(129 140 248);
}
</style>
