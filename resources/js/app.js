import './bootstrap';

import {createApp} from "vue";
import App from '@/App.vue';
import router from '@/router';

import.meta.glob([
    '../images/**',
    '../fonts/**'
])

const app = createApp(App)

app.use(router)

app.mount('#app')
