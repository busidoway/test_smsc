<template>
    <div class="customers">
        <h4 class="py-3 mb-4">Клиенты</h4>
        <div class="card">
            <h5 class="card-header">Клиенты</h5>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ФИО</th>
                                <th>Телефон</th>
                                <th>Дата рождения</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in listCustomers">
                                <td>{{ item.id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.phone }}</td>
                                <td>{{ item.date }}</td>
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

    // data
    let listCustomers = ref([]);
    const loading = ref(true);

    // mounted
    onMounted(() => {
        getCustomers();
    })

    // methods
    function getCustomers() {
        axios.post('/api/customers').then( resp => {
            loading.value = false;
            listCustomers.value = resp.data.customers;
        })
    }
</script>
