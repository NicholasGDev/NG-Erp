<template>
  <div class="app-layout">
    <!-- Overlay mobile -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-black/30 z-30 md:hidden"
      @click="sidebarOpen = false"
    />

    <!-- Botão abrir sidebar (quando fechado) -->
    <button
      v-show="!sidebarOpen"
      class="fixed top-4 left-4 z-50 btn btn-sm btn-square bg-white shadow border border-gray-200"
      @click="sidebarOpen = true"
      aria-label="Abrir menu"
    >
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" :class="{ 'sidebar-collapsed': !sidebarOpen }">
      <div class="h-16 flex items-center justify-between px-5 border-b border-gray-100 flex-shrink-0">
        <RouterLink to="/app/dashboard" class="text-xl font-black text-brand">ngERP</RouterLink>
        <button class="btn btn-ghost btn-xs btn-square" @click="sidebarOpen = false" aria-label="Fechar menu">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <nav class="flex-1 py-4 overflow-y-auto">
        <p class="px-5 mb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Visão Geral</p>
        <RouterLink to="/app/dashboard" class="nav-item" :class="{ active: $route.name === 'dashboard' }" @click="closeMobile">
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Dashboard
        </RouterLink>

        <p class="px-5 mt-4 mb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Estoque</p>
        <RouterLink to="/app/armazens"      class="nav-item" :class="{ active: $route.name === 'armazens' }"      @click="closeMobile">
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          Armazéns
        </RouterLink>
        <RouterLink to="/app/produtos"      class="nav-item" :class="{ active: $route.name === 'produtos' }"      @click="closeMobile">
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          Produtos
        </RouterLink>
        <RouterLink to="/app/movimentacoes" class="nav-item" :class="{ active: $route.name === 'movimentacoes' }" @click="closeMobile">
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
          Movimentações
        </RouterLink>
        <RouterLink to="/app/inventarios"   class="nav-item" :class="{ active: $route.name === 'inventarios' }"   @click="closeMobile">
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
          Inventários
        </RouterLink>

        <p class="px-5 mt-4 mb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Compras</p>
        <RouterLink to="/app/fornecedores"   class="nav-item" :class="{ active: $route.name === 'fornecedores' }"   @click="closeMobile">
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Fornecedores
        </RouterLink>
        <RouterLink to="/app/pedidos-compra" class="nav-item" :class="{ active: $route.name === 'pedidos-compra' }" @click="closeMobile">
          <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Pedidos de Compra
        </RouterLink>
      </nav>

      <div class="p-4 border-t border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs font-black flex-shrink-0">
            {{ userInitial }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-gray-800 truncate">{{ userName }}</p>
            <p class="text-xs text-gray-400 truncate">{{ userEmail }}</p>
          </div>
        </div>
        <button
          class="btn btn-ghost btn-xs w-full text-gray-500 hover:text-red-500"
          @click="handleLogout"
        >
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Sair
        </button>
      </div>
    </aside>

    <!-- Main content -->
    <div class="main-content" :class="{ expanded: !sidebarOpen }">
      <main class="flex-1 p-6">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { logout, currentUser } from '@/api/auth.js';

const router = useRouter();
const sidebarOpen = ref(window.innerWidth >= 768);

const userName    = computed(() => currentUser.value?.name  ?? 'Usuário');
const userEmail   = computed(() => currentUser.value?.email ?? '');
const userInitial = computed(() => userName.value.charAt(0).toUpperCase());

async function handleLogout() {
  await logout();
  router.push('/login');
}

function closeMobile() {
  if (window.innerWidth < 768) sidebarOpen.value = false;
}
</script>
