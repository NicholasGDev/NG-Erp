/**
 * router/index.js — Vue Router with landing page + ERP app (separate layouts)
 */
import { createRouter, createWebHistory } from 'vue-router';

/* ── Layouts ────────────────────────────────────────────────────────── */
import AppLayout from '@/layouts/AppLayout.vue';

/* ── Lazy imports ───────────────────────────────────────────────────── */
const LandingPage        = () => import('@/views/LandingPage.vue');
const Login              = () => import('@/views/auth/Login.vue');
const Register           = () => import('@/views/auth/Register.vue');
const Dashboard          = () => import('@/views/Dashboard.vue');
const Warehouses         = () => import('@/views/estoque/Armazens.vue');
const Suppliers          = () => import('@/views/estoque/Fornecedores.vue');
const Products           = () => import('@/views/estoque/Produtos.vue');
const PurchaseOrders     = () => import('@/views/estoque/PedidosCompra.vue');
const StockMovements     = () => import('@/views/estoque/Movimentacoes.vue');
const PhysicalInventory  = () => import('@/views/estoque/Inventarios.vue');
const NotFound           = () => import('@/views/NotFound.vue');

const routes = [
    /* ── Landing page ───────────────────────────────────────────────── */
    { path: '/', name: 'home', component: LandingPage, meta: { guest: true } },

    /* ── Auth (no sidebar) ──────────────────────────────────────────── */
    { path: '/login',    name: 'login',    component: Login,    meta: { guest: true } },
    { path: '/register', name: 'register', component: Register, meta: { guest: true } },

    /* ── ERP App (with sidebar) — requires auth ─────────────────────── */
    {
        path: '/app',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '',              redirect: '/app/dashboard' },
            { path: 'dashboard',     name: 'dashboard',        component: Dashboard },
            { path: 'armazens',      name: 'warehouses',       component: Warehouses },
            { path: 'fornecedores',  name: 'suppliers',        component: Suppliers },
            { path: 'produtos',      name: 'products',         component: Products },
            { path: 'pedidos-compra',name: 'purchase-orders',  component: PurchaseOrders },
            { path: 'movimentacoes', name: 'stock-movements',  component: StockMovements },
            { path: 'inventarios',   name: 'inventories',      component: PhysicalInventory },
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
    const isAuthenticated = !!localStorage.getItem('ng_jwt');

    if (to.meta.requiresAuth && !isAuthenticated) {
        return { name: 'login', query: { redirect: to.fullPath } };
    }

    if (to.meta.guest && isAuthenticated) {
        return { path: '/app/dashboard' };
    }
});

export default router;
