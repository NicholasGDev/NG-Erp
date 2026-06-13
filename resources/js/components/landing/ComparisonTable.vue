<template>
  <section class="py-20 bg-white" id="comparativo">
    <div class="max-w-4xl mx-auto px-6" data-fade>
      <h2 class="text-3xl md:text-4xl font-black text-gray-900 text-center mb-3">
        Contrate o plano anual e <span class="text-brand">economize até R$ 4 mil</span>
      </h2>
      <p class="text-center text-gray-500 mb-10">Todos os custos de abertura por nossa conta no plano anual.</p>

      <div class="overflow-x-auto rounded-2xl shadow-lg border border-gray-100">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
              <th class="text-left px-6 py-4 font-semibold text-gray-500 w-1/2">Item</th>
              <th class="text-center px-6 py-4 font-semibold text-gray-500">Média do mercado</th>
              <th class="text-center px-6 py-4 font-black text-brand">ngERP</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, i) in rows" :key="i" class="border-b border-gray-50" :class="{ 'bg-gray-50/50': i % 2 !== 0 }">
              <td class="px-6 py-4 text-gray-700" :class="{ 'font-bold text-gray-900': row.highlight }">{{ row.item }}</td>
              <td class="px-6 py-4 text-center text-gray-400 line-through">{{ row.mercado }}</td>
              <td class="px-6 py-4 text-center font-bold text-brand" :class="row.highlight ? 'text-base' : ''">{{ row.ngerp }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="text-center mt-8">
        <RouterLink to="/app/dashboard" class="btn btn-lg rounded-full px-10 font-bold text-white bg-brand">
          Começar agora
        </RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup>
const rows = [
  { item: 'Certificado digital e-CPF',       mercado: 'R$ 120–200', ngerp: 'Custo Zero', highlight: false },
  { item: 'Consultoria e documentação',       mercado: 'R$ 500–600', ngerp: 'Custo Zero', highlight: false },
  { item: 'Taxas governamentais de abertura', mercado: 'R$ 70–150',  ngerp: 'Custo Zero', highlight: false },
  { item: 'Economia total estimada',          mercado: 'Até R$ 950', ngerp: 'Até R$ 4.000', highlight: true },
];
</script>
