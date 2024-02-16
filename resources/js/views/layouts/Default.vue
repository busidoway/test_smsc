<template>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <Aside />
            <div class="layout-page">
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <RouterView />
                    </div>
                </div>
            </div>
        </div>
        <Overlay />
    </div>
</template>

<script setup>
    import Aside from "@/views/layouts/components/Aside.vue";
    import Overlay from "@/views/layouts/components/Overlay.vue";
</script>
