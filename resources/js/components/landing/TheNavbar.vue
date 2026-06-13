<template>
  <header id="saas-nav" class="fixed top-0 left-0 right-0 z-50 bg-white transition-shadow duration-300" :class="{ 'nav-scrolled': scrolled }">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
      <a href="#" class="text-2xl font-black text-brand">ngERP</a>

      <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
        <a href="#solucoes"   class="hover:text-brand transition-colors" style="--tw-text-opacity:1">Soluções</a>
        <a href="#beneficios" class="hover:text-brand transition-colors">Benefícios</a>
        <a href="#precos"     class="hover:text-brand transition-colors">Preços</a>
        <a href="#blog"       class="hover:text-brand transition-colors">Blog</a>
        <a href="#faq"        class="hover:text-brand transition-colors">FAQ</a>
      </nav>

      <div class="flex items-center gap-3">
        <RouterLink to="/login" class="hidden md:inline text-sm font-medium text-gray-600 hover:text-brand transition-colors">Entrar</RouterLink>
        <RouterLink to="/register" class="btn btn-sm bg-brand rounded-full px-5 text-sm font-semibold text-white">Começar grátis</RouterLink>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const scrolled = ref(false);

function onScroll() { scrolled.value = window.scrollY > 10; }

onMounted(() => window.addEventListener('scroll', onScroll));
onUnmounted(() => window.removeEventListener('scroll', onScroll));
</script>
