<template>
    <div class="sendsms">
        <h4 class="py-3 mb-4">Рассылки</h4>
        <div class="card">
            <div class="d-flex justify-content-between align-items-center py-2">
                <h5 class="card-header">Список рассылок</h5>
                <div class="px-4">
                    <RouterLink to="/sendsms/edit" class="btn rounded-pill btn-outline-primary">Добавить</RouterLink>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6 col-md-2 d-flex align-items-center">
                        <span>Дата</span><span class="ms-1 me-2">с</span><input type="date" class="form-control" v-model="dateFrom">
                    </div>
                    <div class="col-sm-6 col-md-2 d-flex align-items-center">
                        <span class="me-2">по</span><input type="date" class="form-control" v-model="dateTo">
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
<!--                            <th>Статус</th>-->
                            <th>Отправлено</th>
                            <th>Доставлено</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        <tr v-for="(item, index) in listSendsms">
                            <td>{{ item.id }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.wait }}</td>
                            <td>{{ item.delivered }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" @click="showDropdown = showDropdown === index ? null : index">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu" style="display: block" v-if="showDropdown === index" ref="target">
                                        <RouterLink class="dropdown-item" :to="'/sendsms/edit/' + item.id"><i class="bx bx-edit-alt me-1"></i> Редактировать</RouterLink>
                                        <a class="dropdown-item cursor-pointer" @click.prevent="deleteItem(item.id)"><i class="bx bx-trash me-1"></i> Удалить</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="spinner-wrap d-flex justify-content-center py-3" v-if="loading">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted, watch} from "vue";
import { onClickOutside } from '@vueuse/core';
import axios from "axios";

// data
const listSendsms = ref([]);
const loading = ref(true);
const dateFrom = ref('');
const dateTo = ref('');
const showDropdown = ref(false);
const target = ref(null)

onClickOutside(target, event => showDropdown.value = false)

// mounted
onMounted(() => {
    getSendsms();
})

// watch
watch(dateFrom,async (newDateFrom) => {

    let dateToVal = '';
    if(dateTo.value) dateToVal = dateTo.value;

    const formData = new FormData();
    const jsonData = JSON.stringify({
        date_from: newDateFrom,
        date_to: dateToVal
    });
    formData.append('data', jsonData);
    axios.post('/api/sendsms', formData).then( resp => {
        console.log(resp.data);
        listSendsms.value = resp.data.sendsms;
    })
})

// methods
function getSendsms() {
    axios.post('/api/sendsms').then( resp => {
        console.log(resp.data);
        loading.value = false;
        listSendsms.value = resp.data.sendsms;
    })
}

</script>
