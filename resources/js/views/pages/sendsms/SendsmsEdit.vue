<template>
    <div class="sendsms-edit">
        <h4 class="py-3 mb-4">Создать рассылку</h4>
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
                        <button class="btn rounded-pill btn-primary" @click.prevent="sendSms">Сохранить</button>
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
import axios from "axios";
import ModalCustomers from "@/views/pages/sendsms/components/ModalCustomers.vue";

// data
const showModal = ref(false);
let arrCustomers = ref([]);
const nameSendsms = ref('');
const messSendsms = ref('');
const typeSendsms = ref('');
const timeSendsms = ref('00:00');
const countDays = ref(0);

// mounted
onMounted(() => {
    typeSendsms.value = 'now';
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
        console.log(resp.data);
    })
}
</script>
