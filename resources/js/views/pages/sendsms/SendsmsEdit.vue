<template>
    <div class="sendsms-edit">
        <h4 class="py-3 mb-4">Создать рассылку</h4>
        <div class="card">
            <div class="card-body">
                <form action="">
                    <div class="mb-3">
                        <label for="nameSendsms" class="form-label">Название</label>
                        <input type="text" id="nameSendsms" class="form-control">
                    </div>
                    <div class="mb-5">
                        <label for="textSendsms" class="form-label">Текст</label>
                        <textarea type="text" id="textSendsms" class="form-control" rows="10"></textarea>
                    </div>
                    <div class="mb-5">
                        <h5>Выберите получателей</h5>
                        <div class=""></div>
                    </div>
                    <div class="mb-5">
                        <h5>Запустите рассылку</h5>
                        <div class="mb-3">
                            <input type="radio" name="type_sendsms" class="form-check-input" id="r_send_now"><label for="r_send_now" class="ms-2 form-check-label">Отправить сейчас</label>
                        </div>
                        <div class="mb-3">
                            <input type="radio" name="type_sendsms" class="form-check-input" id="r_send_auto"><label for="r_send_auto" class="ms-2 form-check-label">Автоматически</label>
                        </div>
                        <div class="mb-3">
                            <input type="radio" name="type_sendsms" class="form-check-input" id="r_send_regular"><label for="r_send_regular" class="ms-2 form-check-label">Регулярно</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn rounded-pill btn-primary">Сохранить</button>
                        <button class="btn rounded-pill btn-outline-secondary ms-4">Назад</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
</script>
