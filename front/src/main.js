/**
 * front/src/main.js — Ponto de entrada do SPA Caronte ERP.
 * Importa o CSS (Tailwind v4 + DaisyUI com dark mode) e monta o app Vue.
 * O alias '@' aponta para front/src/ (configurado em front/vite.config.js).
 */
import './app.css';

import { createApp } from 'vue';
import App    from '@/App.vue';
import router from '@/router/index.js';

createApp(App)
    .use(router)
    .mount('#app');
