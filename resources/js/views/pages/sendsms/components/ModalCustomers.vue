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
                                <td><input type="checkbox" name="" :id="'c_id_'+item.id" class="form-check-input" :value="{'id': item.id, 'name': item.name}" v-model="checkItems"></td>
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
import {ref, onMounted, defineEmits, defineProps} from "vue";
import axios from "axios";

// data
const props = defineProps({
    arrCustomers: Object
});
const emits = defineEmits(['check-items', 'close-modal']);
let listCustomers = ref([]);
let checkItems = ref([]);
const loading = ref(true);

// mounted
onMounted(() => {
    getCustomers();
})

// methods
function getCustomers() {
    let ArrCustomersData = [];
    if(props.arrCustomers.length !== 0) ArrCustomersData = props.arrCustomers;

    const formData = new FormData();
    const jsonData = JSON.stringify(ArrCustomersData);

    formData.append('data', jsonData);

    axios.post('/api/customers', formData).then( resp => {
        loading.value = false;
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
