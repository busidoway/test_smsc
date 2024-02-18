<template>
    <div class="show modal fade" id="modalCustomers" style="display: block">
        <div class="modal-dialog modal-lg">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCustomersTitle">Выбрать контакты</h5>
                    <button type="button" class="btn-close" @click="$emit('close-modal', true)"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="" id="all_customers" class="form-check-input"></th>
                                <th>ID</th>
                                <th>ФИО</th>
                                <th>Телефон</th>
                                <th>Дата рождения</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            <tr v-for="item in listCustomers">
                                <td><input type="checkbox" name="" :id="'c_id_'+item.id" class="form-check-input" :value="item.id" v-model="checkItems"></td>
                                <td>{{ item.id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.phone }}</td>
                                <td>{{ item.date }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" @click="$emit('closeModal', true)">
                        Отмена
                    </button>
                    <button type="button" class="btn btn-primary" @click.prevent="passCheckItems">Добавить</button>
                </div>
            </form>
        </div>
        <div class="content-backdrop fade"></div>
    </div>
</template>

<script setup>
import {ref, onMounted, defineEmits} from "vue";
import axios from "axios";

const props = defineProps({

})
const emits = defineEmits(['check-items', 'close-modal']);
let listCustomers = ref([]);
let checkItems = ref([]);

onMounted(() => {
    getCustomers();
})

function getCustomers() {
    axios.get('/api/customers').then( resp => {
        listCustomers.value = resp.data.customers;
    })
}

function passCheckItems() {
    emits('check-items', checkItems);
    emits('close-modal', true);
}

function checkAllItem() {

}
</script>

<style scoped>
#all_customers {
    width: 18px;
    height: 18px;
}
</style>
