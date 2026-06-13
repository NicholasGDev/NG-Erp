<template>
  <section class="py-20 bg-gray-50" id="blog">
    <div class="max-w-7xl mx-auto px-6" data-fade>
      <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-2">
        Tudo que você precisa saber sobre<br class="hidden md:block"/>
        <span class="text-brand">gestão de estoque e compras</span>
      </h2>
      <p class="text-gray-500 mb-10">Conteúdo gratuito criado por nossos especialistas.</p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <article v-for="post in posts" :key="post.title" class="card bg-white shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
          <div class="bg-gray-200 h-44 flex items-center justify-center text-gray-400 text-sm">{{ post.img }}</div>
          <div class="card-body p-5">
            <span class="text-xs font-bold uppercase text-brand">{{ post.category }}</span>
            <h3 class="font-bold text-gray-900 mt-1 mb-2 leading-tight">{{ post.title }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ post.excerpt }}</p>
            <a href="#" class="text-sm font-semibold hover:underline flex items-center gap-1 text-brand">
              Leia mais
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
        </article>
      </div>

      <div class="text-center mt-10">
        <a href="#" class="btn btn-ghost border border-brand text-brand hover:bg-brand hover:text-white rounded-full font-semibold">
          Ver mais conteúdos
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
const posts = [
  {
    category: 'Gestão de Estoque',
    title:    '5 razões para automatizar o controle de estoque da sua empresa',
    excerpt:  'Descubra como a automação reduz erros, economiza tempo e melhora a precisão do seu inventário.',
    img:      '[Imagem do artigo 1]',
  },
  {
    category: 'Compras',
    title:    'Como otimizar seu processo de pedidos de compra e reduzir custos',
    excerpt:  'Aprenda as melhores práticas para gerenciar fornecedores e negociar melhores condições.',
    img:      '[Imagem do artigo 2]',
  },
  {
    category: 'Inventário',
    title:    'Custo médio vs PEPS: qual método escolher para sua empresa?',
    excerpt:  'Entenda as diferenças entre os métodos de custeio e como cada um impacta seu resultado financeiro.',
    img:      '[Imagem do artigo 3]',
  },
];
</script>
