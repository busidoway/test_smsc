<template>
    <div class="sendsms-edit">
        <h4 class="py-3 mb-4">Создать рассылку</h4>
        <div class="card">
            <div class="card-body">
                <form action="">
                    <div class="mb-3">
                        <label for="name_sendsms" class="form-label">Название</label>
                        <input type="text" id="name_sendsms" class="form-control">
                    </div>
                    <div class="mb-5">
                        <label for="text_sendsms" class="form-label">Текст</label>
                        <textarea type="text" id="text_sendsms" class="form-control" rows="10"></textarea>
                    </div>
                    <div class="mb-5">
                        <h5>Выберите получателей</h5>
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
                            <input type="radio" name="type_sendsms" class="form-check-input" id="r_send_now"><label for="r_send_now" class="ms-2 form-check-label">Отправить сейчас</label>
                        </div>
                        <div class="mb-3">
                            <input type="radio" name="type_sendsms" class="form-check-input" id="r_send_auto"><label for="r_send_auto" class="ms-2 form-check-label">Автоматически</label>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <div class="">
                                <input type="radio" name="type_sendsms" class="form-check-input" id="r_send_regular"><label for="r_send_regular" class="ms-2 form-check-label">Регулярно</label>
                            </div>
                            <div class="d-flex ms-5">
                                <select name="frequency" id="frequency" class="form-select form-select-sm">
                                    <option value="daily" selected>Ежедневно</option>
                                </select>
                                <span class="ms-3">в</span>
                                <input class="form-control form-control-sm ms-3" type="time" value="10:30:00" id="html5-time-input">

                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn rounded-pill btn-primary" @click.once.prevent="sendSms">Сохранить</button>
                        <button class="btn rounded-pill btn-outline-secondary ms-4">Назад</button>
                    </div>
                </form>
            </div>
        </div>
        <ModalCustomers @close-modal="closeModal" @check-items="getCheckItems" v-if="showModal"></ModalCustomers>
    </div>
</template>

<script setup>
import {ref, onMounted} from "vue";
import axios from "axios";
import ModalCustomers from "@/views/pages/sendsms/components/ModalCustomers.vue";

const showModal = ref(false);

function openModal() {
    return showModal.value = true;
}

function closeModal() {
    return showModal.value = false;
}

function getCheckItems(items) {
    console.log(items.value)
}

function sendSms() {
    // axios.post('/api/send_sms').then( resp => {
    //     console.log(resp.data);
    // })
}
</script>
