<template>
  <RouterView />
</template>

<script setup>
// Inicializa o tema assim que o app monta (aplica data-theme no <html>)
import { useTheme } from '@/composables/useTheme.js';
useTheme();
</script>
