<template>
    <div class="sendsms">
        <h4 class="py-3 mb-4">Рассылки</h4>
        <div class="card">
            <div class="d-flex justify-content-between align-items-center py-2">
                <h5 class="card-header">Список рассылок</h5>
                <div class="px-4">
                    <RouterLink to="/sendsms/edit" class="btn rounded-pill btn-outline-primary">Добавить</RouterLink>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Отправлено</th>
                            <th>Доставлено</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        <tr >

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>
