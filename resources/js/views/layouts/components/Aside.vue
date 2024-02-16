<template>
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
            <a href="#" class="app-brand-link">
                <span class="app-brand-logo demo">
                    <img :src="logo" alt="">
                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2">Daily Grow</span>
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>
        <div class="menu-inner-shadow"></div>
        <ul class="menu-inner py-1">
            <RouterLink v-for="(item, index) in listMenu" :to="item.to" custom v-slot="{ href, route, navigate, isActive, isExactActive }">
                <li  class="menu-item" :class="[isActive && 'active', openMenu && index == indexMenu ? 'open' : '']">
                    <div v-if="item.children" class="menu-link" @click="toggleMenu(index)" :class="[ item.children ? 'menu-toggle' : '' ]">
                        <box-icon class="menu-icon" :name="item.icon" color="#697a8d" size="1.25rem"></box-icon>
                        <div>{{ item.title }}</div>
                    </div>
                    <a v-else :href="href" @click="navigate" class="menu-link">
                        <box-icon class="menu-icon" :type="item.typeIcon" :name="item.icon" color="#697a8d" size="1.25rem"></box-icon>
                        <div>{{ item.title }}</div>
                    </a>
                    <ul v-if="item.children" class="menu-sub">
                        <RouterLink v-for="sub_item in item.children" :to="sub_item.to" custom v-slot="{ href, route, navigate, isActive }">
                        <li class="menu-item" :class="[isActive && 'active']">
                            <a :href="href" @click="navigate" class="menu-link">
                                <div data-i18n="Analytics">{{ sub_item.title }}</div>
                            </a>
                        </li>
                        </RouterLink>
                    </ul>
                </li>
            </RouterLink>
        </ul>
    </aside>
</template>

<script setup>
import { ref } from 'vue';
import 'boxicons';

let openMenu = ref(false);
let indexMenu = ref(-1);
const logo = ref('@/images/logo/daily-grow-icon.svg');

const listMenu = ref([
    {
        to: '/',
        title: 'Дашборд',
        icon: 'dashboard',
        typeIcon: 'solid'
    },
    {
        to: '/customers',
        title: 'Клиенты',
        icon: 'id-card'
    }
])

function toggleMenu(index) {
    indexMenu = index;
    openMenu.value = !openMenu.value;
}
</script>
