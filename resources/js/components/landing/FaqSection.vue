<template>
  <section class="py-20 bg-gray-50" id="faq">
    <div class="max-w-3xl mx-auto px-6" data-fade>
      <h2 class="text-3xl md:text-4xl font-black text-gray-900 text-center mb-10">Perguntas frequentes</h2>

      <div class="space-y-3">
        <details
          v-for="item in faqs"
          :key="item.q"
          class="faq-item bg-white border border-gray-100 rounded-xl px-6 py-4 cursor-pointer group"
        >
          <summary class="flex items-center justify-between font-semibold text-gray-800">
            {{ item.q }}
            <svg class="faq-chevron w-5 h-5 text-gray-400 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </summary>
          <p class="mt-3 text-sm text-gray-500 leading-relaxed">{{ item.a }}</p>
        </details>
      </div>
    </div>
  </section>
</template>

<script setup>
const faqs = [
  {
    q: 'O que está incluso na mensalidade?',
    a: 'Todos os nossos planos incluem controle completo de estoque, gestão de compras, inventário físico, movimentações Kardex, suporte especializado e acesso à plataforma 24/7.',
  },
  {
    q: 'Como funciona o controle de lotes?',
    a: 'Cada produto pode ter o controle de lotes ativado. Ao registrar uma movimentação, você informa o número do lote, data de fabricação e validade. O sistema alerta para lotes próximos ao vencimento.',
  },
  {
    q: 'Posso ter múltiplos armazéns?',
    a: 'Sim! Você pode cadastrar quantos armazéns precisar — lojas, depósitos centrais ou armazéns de terceiros — e transferir estoque entre eles com rastreabilidade completa.',
  },
  {
    q: 'Quais métodos de custeio são suportados?',
    a: 'O sistema suporta Custo Médio Ponderado e PEPS (Primeiro a Entrar, Primeiro a Sair). O método é configurado por produto e o custo médio é atualizado automaticamente a cada entrada.',
  },
  {
    q: 'Como funciona a integração com pedidos de compra?',
    a: 'Ao receber um pedido de compra, o sistema registra automaticamente a entrada no estoque e atualiza o custo médio dos produtos. O histórico completo fica disponível no Kardex.',
  },
];
</script>
