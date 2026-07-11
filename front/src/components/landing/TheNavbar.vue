<template>
  <header id="saas-nav" class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 transition-shadow duration-300" :class="{ 'nav-scrolled': scrolled }">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
      <a href="#" class="flex items-center gap-2">
        <img src="@/assets/logo-rounded.png" alt="Caronte ERP" class="h-8 w-8" />
        <span class="text-xl font-black text-brand">Caronte ERP</span>
      </a>

      <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600 dark:text-gray-300">
        <a href="#solucoes"   class="hover:text-brand transition-colors">Soluções</a>
        <a href="#beneficios" class="hover:text-brand transition-colors">Benefícios</a>
        <a href="#precos"     class="hover:text-brand transition-colors">Preços</a>
        <a href="#blog"       class="hover:text-brand transition-colors">Blog</a>
        <a href="#faq"        class="hover:text-brand transition-colors">FAQ</a>
      </nav>

      <div class="flex items-center gap-3">
        <!-- Dark mode toggle -->
        <button
          class="p-2 rounded-lg text-gray-500 hover:text-brand transition-colors"
          :aria-label="isDark() ? 'Modo claro' : 'Modo escuro'"
          @click="toggleTheme"
        >
          <svg v-if="isDark()" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
          </svg>
        </button>
        <RouterLink to="/login" class="hidden md:inline text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand transition-colors">Entrar</RouterLink>
        <RouterLink to="/register" class="btn btn-sm bg-brand rounded-full px-5 text-sm font-semibold text-white">Começar grátis</RouterLink>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useTheme } from '@/composables/useTheme.js';

const scrolled = ref(false);
const { isDark, toggle: toggleTheme } = useTheme();

function onScroll() { scrolled.value = window.scrollY > 10; }

onMounted(() => window.addEventListener('scroll', onScroll));
onUnmounted(() => window.removeEventListener('scroll', onScroll));
</script>
