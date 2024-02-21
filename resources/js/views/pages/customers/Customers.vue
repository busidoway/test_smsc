<template>
    <div class="customers">
        <h4 class="py-3 mb-4">Клиенты</h4>
        <div class="card">
            <div class="d-flex justify-content-between align-items-center py-2">
                <h5 class="card-header">Клиенты</h5>
                <div class="px-4">
                    <RouterLink to="/customers/edit" class="btn rounded-pill btn-outline-primary">Добавить</RouterLink>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ФИО</th>
                                <th>Телефон</th>
                                <th>Дата рождения</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="(item, index) in listCustomers">
                                <td>{{ item.id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.phone }}</td>
                                <td>{{ item.date }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" @click="showDropdown = showDropdown === index ? null : index">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" style="display: block" v-if="showDropdown === index" ref="target">
                                            <RouterLink class="dropdown-item" :to="'/customers/edit/' + item.id"><i class="bx bx-edit-alt me-1"></i> Редактировать</RouterLink>
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
    import {ref, onMounted} from "vue";
    import axios from "axios";
    import { onClickOutside } from '@vueuse/core';

    // data
    let listCustomers = ref([]);
    const loading = ref(true);
    const showDropdown = ref(false);
    const target = ref(null)

    // mounted
    onMounted(() => {
        getCustomers();
    })

    onClickOutside(target, event => showDropdown.value = false)

    // methods
    function getCustomers() {
        axios.post('/api/customers').then( resp => {
            loading.value = false;
            listCustomers.value = resp.data.customers;
        })
    }

    function deleteItem(id) {
        if(id){
            axios.post('/api/customers_delete/' + id).then( resp => {
                if(resp.data.status === 'success'){
                    loading.value = true;
                    getCustomers();
                }
            })
        }
    }
</script>
