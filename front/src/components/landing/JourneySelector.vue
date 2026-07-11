<template>
  <section class="py-20 bg-gray-50" id="solucoes">
    <div class="max-w-7xl mx-auto px-6" data-fade>
      <h2 class="text-3xl md:text-4xl font-black text-base-content text-center mb-2">
        Soluções para cada etapa da sua jornada
      </h2>
      <p class="text-center text-gray-500 mb-10">Comece do zero, migre do MEI ou troque de contador sem complicações.</p>

      <!-- Tabs -->
      <div class="flex justify-center gap-0 border-b border-gray-200 mb-10">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          class="journey-tab px-6 py-3 text-sm font-medium text-gray-500 hover:text-brand transition-colors"
          :class="{ active: activeTab === tab.key }"
          @click="activeTab = tab.key"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Painel ativo -->
      <div class="flex flex-col md:flex-row items-center gap-10">
        <div class="flex-1 order-2 md:order-1">
          <span class="inline-block bg-green-50 text-brand text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase">
            {{ activePanel.badge }}
          </span>
          <h3 class="text-2xl font-black text-base-content mb-4">{{ activePanel.title }}</h3>
          <ul class="space-y-3 mb-6">
            <li v-for="item in activePanel.items" :key="item" class="flex items-start gap-3 text-sm text-gray-600">
              <span class="w-5 h-5 bg-brand text-white rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">✓</span>
              {{ item }}
            </li>
          </ul>
          <div class="flex gap-3">
            <RouterLink :to="activePanel.link" class="btn bg-brand rounded-full font-bold text-white">
              {{ activePanel.cta }}
            </RouterLink>
            <a href="#faq" class="btn btn-ghost rounded-full">Ainda tenho dúvidas</a>
          </div>
        </div>

        <div class="flex-1 order-1 md:order-2">
          <div class="bg-gray-200 rounded-2xl h-64 md:h-80 flex items-center justify-center text-gray-400 text-sm">
            {{ activePanel.illustration }}
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';

const activeTab = ref('estoque');

const tabs = [
  { key: 'estoque',  label: 'Controle de Estoque' },
  { key: 'compras',  label: 'Gestão de Compras' },
  { key: 'inventario', label: 'Inventário & Kardex' },
];

const panels = {
  estoque: {
    badge: 'Módulo de estoque',
    title: 'Controle total do seu estoque em tempo real',
    items: [
      'Cadastre armazéns, corredores, prateleiras e posições',
      'Gerencie produtos com SKU, unidade de medida e custo médio',
      'Controle de lotes com data de fabricação e validade',
      'Método de custeio PEPS ou Custo Médio por produto',
    ],
    cta: 'Acessar estoque',
    link: '/app/produtos',
    illustration: '[Dashboard de estoque]',
  },
  compras: {
    badge: 'Módulo de compras',
    title: 'Pedidos de compra com rastreabilidade completa',
    items: [
      'Cadastro de fornecedores com CNPJ e condições de pagamento',
      'Emissão de pedidos de compra com status em tempo real',
      'Controle de itens por pedido com quantidade e custo unitário',
      'Acompanhamento de entregas parciais e conclusão',
    ],
    cta: 'Ver pedidos',
    link: '/app/pedidos-compra',
    illustration: '[Pedido de compra]',
  },
  inventario: {
    badge: 'Módulo de inventário',
    title: 'Inventário físico e Kardex automatizados',
    items: [
      'Registre movimentações: entrada, saída, transferência e ajustes',
      'Kardex com saldo atualizado após cada movimentação',
      'Contagem física vs. saldo do sistema com divergência calculada',
      'Ajuste de estoque com rastreabilidade de responsável',
    ],
    cta: 'Ver inventários',
    link: '/app/inventarios',
    illustration: '[Kardex de movimentações]',
  },
};

const activePanel = computed(() => panels[activeTab.value]);
</script>
