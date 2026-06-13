/**
 * router/index.js — Vue Router com landing page + ERP app (layouts separados)
 */
import { createRouter, createWebHistory } from 'vue-router';

/* ── Layouts ────────────────────────────────────────────────────────── */
import AppLayout from '@/layouts/AppLayout.vue';

/* ── Lazy imports ───────────────────────────────────────────────────── */
const LandingPage   = () => import('@/views/LandingPage.vue');
const Login         = () => import('@/views/auth/Login.vue');
const Register      = () => import('@/views/auth/Register.vue');
const Dashboard     = () => import('@/views/Dashboard.vue');
const Armazens      = () => import('@/views/estoque/Armazens.vue');
const Fornecedores  = () => import('@/views/estoque/Fornecedores.vue');
const Produtos      = () => import('@/views/estoque/Produtos.vue');
const PedidosCompra = () => import('@/views/estoque/PedidosCompra.vue');
const Movimentacoes = () => import('@/views/estoque/Movimentacoes.vue');
const Inventarios   = () => import('@/views/estoque/Inventarios.vue');
const NotFound      = () => import('@/views/NotFound.vue');

const routes = [
    /* ── Landing page ───────────────────────────────────────────────── */
    { path: '/', name: 'home', component: LandingPage, meta: { guest: true } },

    /* ── Auth (sem sidebar) ─────────────────────────────────────────── */
    { path: '/login',    name: 'login',    component: Login,    meta: { guest: true } },
    { path: '/register', name: 'register', component: Register, meta: { guest: true } },

    /* ── ERP App (com sidebar) — requer autenticação ────────────────── */
    {
        path: '/app',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '',              redirect: '/app/dashboard' },
            { path: 'dashboard',     name: 'dashboard',       component: Dashboard },
            { path: 'armazens',      name: 'armazens',        component: Armazens },
            { path: 'fornecedores',  name: 'fornecedores',    component: Fornecedores },
            { path: 'produtos',      name: 'produtos',        component: Produtos },
            { path: 'pedidos-compra',name: 'pedidos-compra',  component: PedidosCompra },
            { path: 'movimentacoes', name: 'movimentacoes',   component: Movimentacoes },
            { path: 'inventarios',   name: 'inventarios',     component: Inventarios },
        ],
    },

    /* ── 404 ─────────────────────────────────────────────────────────── */
    { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior: (to, from, saved) => saved ?? { top: 0 },
});

/* ── Navigation guard ───────────────────────────────────────────────── */
router.beforeEach((to) => {
    const isAuth = !!localStorage.getItem('ng_jwt');

    if (to.meta.requiresAuth && !isAuth) {
        return { name: 'login', query: { redirect: to.fullPath } };
    }

    if (to.meta.guest && isAuth) {
        return { path: '/app/dashboard' };
    }
});

export default router;
