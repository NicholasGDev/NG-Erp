<template>
  <section class="py-20 bg-base-100" id="depoimentos">
    <div class="max-w-7xl mx-auto px-6" data-fade>
      <div class="flex items-center justify-between mb-10">
        <h2 class="text-3xl md:text-4xl font-black text-base-content">O que nossos clientes falam</h2>
        <div class="hidden md:flex items-center gap-2 text-sm text-gray-500">
          <span class="font-bold text-gray-700">4.7</span>
          <div class="flex text-yellow-400">★★★★★</div>
          <span>+2.000 avaliações</span>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="t in testimonials" :key="t.name" class="card bg-gray-50 border border-base-200 p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg">
              {{ t.name[0] }}
            </div>
            <div>
              <p class="font-bold text-base-content text-sm">{{ t.name }}</p>
              <p class="text-xs text-gray-500">{{ t.role }}</p>
            </div>
          </div>
          <div class="flex text-yellow-400 text-xs mb-3">★★★★★</div>
          <p class="text-sm text-gray-600 leading-relaxed">"{{ t.text }}"</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const testimonials = [
  { name: 'Ana Luisa Garcia',     role: 'Médica · cliente desde 2017',                  text: 'Abri minha empresa sem sair de casa e de forma simples e prática. Resolvo dúvidas via e-mail e videochamada com agilidade.' },
  { name: 'Raphael Ligmanovski',  role: 'Consultor de sistemas · cliente desde 2022',   text: 'Consigo fazer a gestão do CNPJ em poucos passos e otimizo meu tempo. Até pagar os impostos do meu CNPJ ficou fácil.' },
  { name: 'Bruna Ricci',          role: 'Analista administrativa · cliente desde 2022', text: 'A abertura da minha empresa foi simples, online e com um atendimento completo e humano. Recomendo muito.' },
  { name: 'Felipe Bernardo',      role: 'Arquiteto · cliente desde 2021',               text: 'Me ajudou a deixar a gestão do CNPJ mais rápida. Sobra muito mais tempo para focar no meu negócio e nos clientes.' },
  { name: 'Vitor de Sousa',       role: 'Consultor · cliente desde 2023',               text: 'Não preciso me preocupar com a parte burocrática de ser PJ porque a equipe cuida de tudo isso para mim.' },
  { name: 'Telma Sturlini',       role: 'Psicóloga · cliente desde 2021',               text: 'A facilidade e a forma amistosa de interação com quem está abrindo empresa são os principais motivos de eu ser cliente.' },
];
</script>
