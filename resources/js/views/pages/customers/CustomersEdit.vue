<template>
    <div class="customers-edit">
        <div class="py-3 mb-4 d-flex align-items-center">
            <h4 class="m-0" v-if="editMode">Редактирование</h4>
            <h4 class="m-0" v-else>Создать контакт</h4>
            <div class="badge bg-label-success mt-2 ms-3" v-if="badge.success">Успешно сохранено</div>
            <div class="badge bg-label-danger mt-2 ms-3" v-if="badge.error">Ошибка</div>
        </div>
        <div class="card">
            <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">ФИО</label>
                        <input type="text" name="name" id="name" class="form-control" v-model="name" required>
                    </div>
                    <div class="mb-5 row">
                        <div class="col-6">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="text" name="phone" id="phone" class="form-control" v-model="phone" required>
                        </div>
                        <div class="col-6">
                            <label for="date_birth" class="form-label">Дата рождения</label>
                            <input type="date" name="date_birth" id="date_birth" class="form-control" v-model="dateBirth" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn rounded-pill btn-primary" @click.prevent="update(route.params.id)" v-if="editMode">Сохранить</button>
                        <button class="btn rounded-pill btn-primary" @click.prevent="save" v-else>Сохранить</button>
                        <RouterLink to="/customers" class="btn rounded-pill btn-outline-secondary ms-4">Назад</RouterLink>
                    </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {ref, onMounted} from "vue";
import { useRoute, useRouter } from 'vue-router';
import axios from "axios";

// data
const route = useRoute();
const router = useRouter();
const name = ref('');
const phone = ref('');
const dateBirth = ref('');
const editMode = ref(false);
const badge = ref({
    success: false,
    error: false
});

// mounted
onMounted(() => {
    edit(route.params.id);
})

// methods
function save(){
    const formData = new FormData();
    const jsonData = JSON.stringify({
        name: name.value,
        phone: phone.value,
        date: dateBirth.value
    });

    formData.append('data', jsonData);

    axios.post('/api/customers_store', formData).then( resp => {
        if(resp.data.status === 'success'){
            badge.value.success = true;
            router.push('/customers/edit/' + resp.data.customer.id);
        }
    })
}

function edit(id){
    if(id) {
        axios.get('/api/customers_edit/' + id).then( resp => {
            if(resp.data.customer.length !== 0){
                name.value = resp.data.customer.name;
                phone.value = resp.data.customer.phone;
                dateBirth.value = resp.data.customer.date;
                editMode.value = true;
            }
        })
    }
}

function update(id){
    if(id) {
        const formData = new FormData();
        const jsonData = JSON.stringify({
            id: id,
            name: name.value,
            phone: phone.value,
            date: dateBirth.value
        });

        formData.append('data', jsonData);

        axios.post('/api/customers_update/' + id, formData).then( resp => {
            if(resp.data.status === 'success'){
                badge.value.error = false;
                badge.value.success = true;
            }else if(resp.data.status === 'error'){
                badge.value.success = false;
                badge.value.error = true;
            }
        })
    }
}
</script>
