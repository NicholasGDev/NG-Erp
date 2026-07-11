<template>
  <div>
    <h2 class="text-xl font-black text-gray-900 mb-6">Visão Geral do Estoque</h2>

    <!-- KPI cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div v-for="kpi in kpis" :key="kpi.label" class="card bg-white border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ kpi.label }}</p>
        <p class="text-2xl font-black text-gray-900">{{ kpi.value }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ kpi.sub }}</p>
      </div>
    </div>

    <!-- Links rápidos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <RouterLink
        v-for="link in quickLinks"
        :key="link.to"
        :to="link.to"
        class="card bg-white border border-gray-100 shadow-sm p-5 hover:border-green-400 transition-colors group"
      >
        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center mb-3 group-hover:bg-green-500 transition-colors text-brand">
          <component :is="link.icon" class="w-5 h-5 group-hover:text-white" />
        </div>
        <p class="font-bold text-gray-900 text-sm">{{ link.label }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ link.desc }}</p>
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const kpis = ref([
  { label: 'Armazéns',          value: '—', sub: 'cadastrados' },
  { label: 'Produtos',          value: '—', sub: 'ativos' },
  { label: 'Pedidos Pendentes', value: '—', sub: 'em aberto' },
  { label: 'Movimentações',     value: '—', sub: 'hoje' },
]);

const quickLinks = [
  { to: '/produtos',       label: 'Produtos',          desc: 'Gerencie o catálogo de produtos' },
  { to: '/pedidos-compra', label: 'Pedidos de Compra',  desc: 'Emita e acompanhe pedidos' },
  { to: '/movimentacoes',  label: 'Movimentações',      desc: 'Kardex — entradas e saídas' },
];
</script>
