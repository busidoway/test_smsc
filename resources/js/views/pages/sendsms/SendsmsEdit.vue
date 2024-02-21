<template>
    <div class="sendsms-edit">
        <div class="py-3 mb-4 d-flex align-items-center">
            <h4 class="m-0" v-if="editMode">Редактирование</h4>
            <h4 class="m-0" v-else>Создать рассылку</h4>
            <div class="badge bg-label-success mt-2 ms-3" v-if="badge.success">Успешно сохранено</div>
            <div class="badge bg-label-danger mt-2 ms-3" v-if="badge.error">Ошибка</div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="name_sendsms" class="form-label">Название</label>
                        <input type="text" name="name" id="name_sendsms" class="form-control" v-model="nameSendsms">
                    </div>
                    <div class="mb-5">
                        <label for="text_sendsms" class="form-label">Текст</label>
                        <textarea type="text" name="message" id="mess_sendsms" class="form-control" rows="10" v-model="messSendsms"></textarea>
                    </div>
                    <div class="mb-5">
                        <h5>Список получателей</h5>
                        <div class="mb-3">
                            <div class="mb-2" v-for="item in arrCustomers">
                            <input type="checkbox" name="" class="form-check-input" :id="'customer_'+item.id" :value="item.id">
                            <label :for="'customer_'+item.id" class="ms-2 form-check-label">{{ item.name }}</label>
                        </div>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-outline-primary btn-add-customer" @click.prevent="openModal">
                                <i class='bx bxs-user-plus'></i>
                                <span class="ms-1">Добавить контакты</span>
                            </button>
                        </div>
                    </div>
                    <div class="mb-5">
                        <h5>Запустите рассылку</h5>
                        <div class="mb-3">
                            <input type="radio" name="type_sendsms" class="form-check-input" id="r_send_now" value="now" v-model="typeSendsms">
                            <label for="r_send_now" class="ms-2 form-check-label">Отправить сейчас</label>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <div class="">
                                <input type="radio" name="type_sendsms" class="form-check-input" id="r_send_regular" value="reg" v-model="typeSendsms">
                                <label for="r_send_regular" class="ms-2 form-check-label">Регулярно</label>
                            </div>
                            <div class="d-flex ms-5">
                                <select name="frequency" id="frequency" class="form-select form-select-sm">
                                    <option value="daily" selected>Ежедневно</option>
                                </select>
                                <span class="ms-3">в</span>
                                <input class="form-control form-control-sm ms-3" type="time" value="10:30:00" v-model="timeSendsms" id="html5-time-input">
                                <select name="template" id="template" class="form-select form-select-sm ms-3">
                                    <option value="1" selected>За N дней до дня рождения</option>
                                </select>
                                <span class="ms-3 d-inline me-2">дней:</span>
                                <input type="number" v-model="countDays" class="form-control form-control-sm" min="0" value="0" style="width: 60px;">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn rounded-pill btn-primary" @click.prevent="update(route.params.id)" v-if="editMode">Сохранить</button>
                        <button class="btn rounded-pill btn-primary" @click.prevent="sendSms" v-else>Сохранить</button>
                        <RouterLink to="/sendsms" class="btn rounded-pill btn-outline-secondary ms-4">Назад</RouterLink>
                    </div>
                </form>
            </div>
        </div>
        <ModalCustomers @close-modal="closeModal" @check-items="getCheckItems" :arr-customers="arrCustomers" v-if="showModal"></ModalCustomers>
    </div>
</template>

<script setup>
import {ref, onMounted} from "vue";
import { useRoute, useRouter } from 'vue-router';
import axios from "axios";
import ModalCustomers from "@/views/pages/sendsms/components/ModalCustomers.vue";

// data
const route = useRoute();
const router = useRouter();
const showModal = ref(false);
let arrCustomers = ref([]);
const nameSendsms = ref('');
const messSendsms = ref('');
const typeSendsms = ref('');
const timeSendsms = ref('00:00');
const countDays = ref(0);
const editMode = ref(false);
const badge = ref({
    success: false,
    error: false
});

// mounted
onMounted(() => {
    typeSendsms.value = 'now';
    edit(route.params.id);
})

// methods
function openModal() {
    return showModal.value = true;
}

function closeModal() {
    return showModal.value = false;
}

function getCheckItems(items) {
    // console.log(items)
    if(items.value) arrCustomers = items.value;
}

function sendSms() {

    let arrCustomersData = [];
    if(!arrCustomers.value) arrCustomersData = arrCustomers;

    const formData = new FormData();
    const jsonData = JSON.stringify({
        name: nameSendsms.value,
        message: messSendsms.value,
        time: timeSendsms.value,
        customers: arrCustomersData,
        type: typeSendsms.value,
        count_days: countDays.value
    });

    formData.append('data', jsonData);

    axios.post('/api/sendsms_store', formData).then( resp => {
        if(resp.data.status === 'success'){
            badge.value.error = false;
            badge.value.success = true;
            router.push('/sendsms/edit/' + resp.data.sendsms.id);
        }else if(resp.data.status === 'error'){
            badge.value.success = false;
            badge.value.error = true;
        }
    })
}

function edit(id){
    if(id) {
        axios.get('/api/sendsms_edit/' + id).then( resp => {
            if(resp.data.sendsms.length !== 0){
                nameSendsms.value = resp.data.sendsms.name;
                messSendsms.value = resp.data.sendsms.message;
                timeSendsms.value = resp.data.sendsms.time;
                countDays.value = resp.data.sendsms.count_days;

                if(timeSendsms.value && countDays.value)
                    typeSendsms.value = 'reg';

                editMode.value = true;
            }
            if(resp.data.customers.length !== 0){
                arrCustomers.value = resp.data.customers;
            }
        })
    }
}

function update(id) {
    if(id) {
        let arrCustomersData = [];
        if (!arrCustomers.value) arrCustomersData = arrCustomers;

        const formData = new FormData();
        const jsonData = JSON.stringify({
            name: nameSendsms.value,
            message: messSendsms.value,
            time: timeSendsms.value,
            customers: arrCustomersData,
            type: typeSendsms.value,
            count_days: countDays.value
        });

        formData.append('data', jsonData);

        axios.post('/api/sendsms_update/' + id, formData).then(resp => {
            if (resp.data.status === 'success') {
                badge.value.error = false;
                badge.value.success = true;
            } else if (resp.data.status === 'error') {
                badge.value.success = false;
                badge.value.error = true;
            }
        })
    }
}
</script>
