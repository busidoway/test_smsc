import { createRouter, createWebHistory } from 'vue-router'

const index = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        { path: '/', component: () => import('@/views/pages/dashboard/Dashboard.vue') },
        { path: '/customers', component: () => import('@/views/pages/customers/Customers.vue') },
        { path: '/sendsms', component: () => import('@/views/pages/sendsms/Sendsms.vue') },
        { path: '/sendsms/edit', component: () => import('@/views/pages/sendsms/SendsmsEdit.vue') },
    ]
})

export default index
