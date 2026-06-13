<template>
  <footer class="bg-gray-900 text-gray-400 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">

        <!-- Marca -->
        <div class="col-span-2">
          <p class="text-white text-2xl font-black mb-2">ngERP</p>
          <p class="text-sm mb-4">Simplificando a gestão de quem empreende.<br/>Desde 2026.</p>
          <div class="flex gap-3">
            <a v-for="social in socials" :key="social.label"
              href="#"
              :aria-label="social.label"
              class="w-8 h-8 bg-gray-800 hover:bg-brand rounded-lg flex items-center justify-center transition-colors text-gray-400 hover:text-white"
            >
              <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" v-html="social.svg" />
            </a>
          </div>
        </div>

        <!-- Links -->
        <div v-for="col in columns" :key="col.title">
          <p class="text-white font-semibold text-sm mb-3 uppercase tracking-wider">{{ col.title }}</p>
          <ul class="space-y-2 text-sm">
            <li v-for="link in col.links" :key="link">
              <a href="#" class="hover:text-white transition-colors">{{ link }}</a>
            </li>
          </ul>
        </div>

      </div>

      <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-3 text-xs">
        <p>© 2026 ngERP. Todos os direitos reservados.</p>
        <p>Simplificando a gestão de quem empreende no Brasil.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
const socials = [
  { label: 'YouTube',   svg: '<path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>' },
  { label: 'LinkedIn',  svg: '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>' },
  { label: 'Instagram', svg: '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>' },
];

const columns = [
  { title: 'A empresa', links: ['Sobre nós', 'Blog', 'Carreiras', 'Imprensa'] },
  { title: 'Módulos',   links: ['Estoque', 'Pedidos de Compra', 'Inventário', 'Kardex'] },
  { title: 'Legal',     links: ['Termos de uso', 'Privacidade', 'Cookies', 'Canal de ética'] },
];
</script>
