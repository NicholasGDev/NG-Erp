<template>
  <Teleport to="body">
    <!-- Overlay -->
    <Transition name="drawer-overlay">
      <div
        v-if="modelValue"
        class="fixed inset-0 bg-black/40 z-40"
        @click="$emit('update:modelValue', false)"
      />
    </Transition>

    <!-- Painel lateral -->
    <Transition name="drawer-slide">
      <div
        v-if="modelValue"
        class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-50 flex flex-col shadow-2xl"
      >
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
          <h3 class="font-black text-lg text-gray-900">{{ title }}</h3>
          <button
            class="btn btn-ghost btn-sm btn-square text-gray-400 hover:text-gray-700"
            @click="$emit('update:modelValue', false)"
            aria-label="Fechar"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Conteúdo (scroll) -->
        <div class="flex-1 overflow-y-auto px-6 py-5">
          <slot />
        </div>

        <!-- Footer com ações -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 flex-shrink-0">
          <slot name="footer" />
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
defineProps({ modelValue: Boolean, title: String });
defineEmits(['update:modelValue']);
</script>
