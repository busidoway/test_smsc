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
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ref, onMounted} from "vue";
    import axios from "axios";

    let listCustomers = ref([]);

    onMounted(() => {
        getCustomers();
    })

    function getCustomers() {
        axios.get('/api/customers').then( resp => {
            listCustomers.value = resp.data.customers;
        })
    }
</script>
