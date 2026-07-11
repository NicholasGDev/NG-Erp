<template>
  <section class="pt-28 pb-16 bg-base-100" id="inicio">
    <div class="max-w-7xl mx-auto px-6">

      <div class="text-center max-w-3xl mx-auto mb-12" data-fade>
        <span class="inline-block bg-green-50 text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wider text-brand">Novidade</span>
        <div class="flex items-center justify-center gap-4 mb-4">
          <img src="@/assets/logo-rounded.png" alt="Caronte ERP" class="h-16 w-16" />
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-base-content leading-tight">Caronte ERP</h1>
        </div>
        <p class="text-lg text-gray-500 mb-6">Plataforma completa de gestão empresarial — estoque, compras, fornecedores e inventário em um só lugar.</p>
        <p class="text-sm font-semibold text-gray-700">Planos a partir de <span class="text-xl font-black text-brand">R$ 99/mês</span></p>
      </div>

      <!-- 3 jornadas -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto" data-fade>

        <div v-for="card in cards" :key="card.title"
          class="card bg-base-100 shadow-lg border border-base-200 hover:border-brand transition-colors cursor-pointer group p-6"
          :class="{ 'md:ring-2 ring-2 ring-brand': card.featured }"
        >
          <div class="w-10 h-10 bg-green-50 text-brand rounded-xl flex items-center justify-center mb-4 group-hover:text-white transition-colors"
            :style="card.featured ? '' : ''"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon"/>
            </svg>
          </div>
          <span v-if="card.featured" class="inline-block text-white text-xs px-2 py-0.5 rounded-full mb-2 font-bold bg-brand">Mais popular</span>
          <h3 class="font-bold text-base-content mb-2">{{ card.title }}</h3>
          <p class="text-sm text-gray-500 mb-4">{{ card.desc }}</p>
          <RouterLink :to="card.link" class="text-sm font-semibold hover:underline flex items-center gap-1 text-brand">
            {{ card.cta }}
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </RouterLink>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
const cards = [
  {
    title: 'Controle de Estoque',
    desc:  'Gerencie armazéns, produtos, lotes e posições com rastreabilidade completa.',
    icon:  'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    cta:   'Acessar estoque',
    link:  '/app/produtos',
    featured: false,
  },
  {
    title: 'Gestão de Compras',
    desc:  'Emita pedidos de compra, controle fornecedores e acompanhe entregas.',
    icon:  'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    cta:   'Ver pedidos',
    link:  '/app/pedidos-compra',
    featured: true,
  },
  {
    title: 'Inventário & Kardex',
    desc:  'Realize contagens físicas, registre movimentações e mantenha o Kardex atualizado.',
    icon:  'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
    cta:   'Ver inventários',
    link:  '/app/inventarios',
    featured: false,
  },
];
</script>
