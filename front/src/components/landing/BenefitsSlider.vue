<template>
  <section class="py-20 bg-base-100" id="beneficios">
    <div class="max-w-7xl mx-auto px-6" data-fade>
      <h2 class="text-3xl md:text-4xl font-black text-base-content mb-2">
        Caronte ERP · <span class="text-brand">muito mais que gestão de estoque</span>
      </h2>
      <p class="text-gray-500 mb-8">Tudo que você precisa para controlar seu negócio, numa única plataforma.</p>

      <div class="benefit-track">
        <div v-for="card in cards" :key="card.title" class="benefit-card card bg-base-100 shadow-md border border-base-200 p-6">
          <div class="w-10 h-10 bg-green-50 text-brand rounded-xl flex items-center justify-center mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon"/>
            </svg>
          </div>
          <h4 class="font-bold text-base-content mb-2">{{ card.title }}</h4>
          <p class="text-sm text-gray-500">{{ card.desc }}</p>
        </div>
      </div>

      <div class="text-center mt-8">
        <RouterLink to="/app/dashboard" class="btn btn-outline border-brand text-brand hover:bg-brand hover:text-white rounded-full font-bold">
          Acessar o sistema
        </RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup>
const cards = [
  {
    title: 'Armazéns & Posições',
    desc:  'Estruture seu armazém com corredores, prateleiras e níveis. Saiba exatamente onde cada produto está.',
    icon:  'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
  },
  {
    title: 'Controle de Lotes',
    desc:  'Rastreie lotes com data de fabricação e validade. Alerte para produtos próximos do vencimento.',
    icon:  'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
  },
  {
    title: 'Custo Médio / PEPS',
    desc:  'Métodos de custeio automáticos. O custo médio é atualizado a cada entrada no estoque.',
    icon:  'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
  },
  {
    title: 'Pedidos de Compra',
    desc:  'Emita e acompanhe pedidos do rascunho ao recebimento. Controle quantidades previstas vs. recebidas.',
    icon:  'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
  },
  {
    title: 'Movimentações & Kardex',
    desc:  'Registro completo de entradas, saídas, transferências e ajustes com saldo atualizado em tempo real.',
    icon:  'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
  },
];
</script>
