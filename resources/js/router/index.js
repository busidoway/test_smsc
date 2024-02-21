import { createRouter, createWebHistory } from 'vue-router'

const index = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        { path: '/dashboard', component: () => import('@/views/pages/dashboard/Dashboard.vue') },
        { path: '/customers', component: () => import('@/views/pages/customers/Customers.vue') },
        { path: '/sendsms', component: () => import('@/views/pages/sendsms/Sendsms.vue') },
        { path: '/sendsms/edit', component: () => import('@/views/pages/sendsms/SendsmsEdit.vue') },
        { path: '/sendsms/edit/:id', component: () => import('@/views/pages/sendsms/SendsmsEdit.vue') },
        { path: '/customers/edit', component: () => import('@/views/pages/customers/CustomersEdit.vue') },
        { path: '/customers/edit/:id', component: () => import('@/views/pages/customers/CustomersEdit.vue') },
    ]
})

export default index
