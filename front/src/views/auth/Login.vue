<template>
  <div class="min-h-screen flex">

    <!-- Painel esquerdo — branding -->
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-brand relative overflow-hidden">
      <!-- Círculos decorativos -->
      <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-base-100/10 pointer-events-none"></div>
      <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full bg-base-100/10 pointer-events-none"></div>

      <RouterLink to="/" class="flex items-center gap-3 z-10">
        <img src="@/assets/logo.png" alt="Caronte ERP" class="h-30 w-30" />
        <span class="text-white text-2xl font-black tracking-tight">Caronte ERP</span>
      </RouterLink>

      <div class="z-10">
        <h2 class="text-white text-4xl font-black leading-tight mb-4">
          Controle total do<br>seu estoque.
        </h2>
        <p class="text-green-100 text-lg mb-8">
          Armazéns, compras, inventário e muito mais — tudo em um só lugar.
        </p>

        <div class="space-y-3">
          <div v-for="item in features" :key="item" class="flex items-center gap-3">
            <span class="w-5 h-5 rounded-full bg-base-100/20 flex items-center justify-center flex-shrink-0">
              <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
              </svg>
            </span>
            <span class="text-green-50 text-sm font-medium">{{ item }}</span>
          </div>
        </div>
      </div>

      <p class="text-green-200 text-xs z-10">© 2026 Caronte ERP · Todos os direitos reservados</p>
    </div>

    <!-- Painel direito — formulário -->
    <div class="flex-1 flex items-center justify-center p-6 bg-base-100">
      <div class="w-full max-w-md">

        <!-- Logo mobile -->
        <RouterLink to="/" class="lg:hidden flex items-center justify-center gap-2 mb-8">
          <img src="@/assets/logo-rounded.png" alt="Caronte ERP" class="h-8 w-8" />
          <span class="text-brand text-2xl font-black">Caronte ERP</span>
        </RouterLink>

        <div class="mb-8">
          <h1 class="text-2xl font-black text-base-content mb-1">Bem-vindo de volta</h1>
          <p class="text-gray-500 text-sm">Acesse sua conta para continuar</p>
        </div>

        <!-- Alerta de erro -->
        <div v-if="error" class="alert alert-error mb-6 text-sm py-3">
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/>
          </svg>
          {{ error }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- E-mail -->
          <div class="form-control">
            <label class="label pb-1">
              <span class="label-text font-semibold text-gray-700">E-mail</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder="seu@email.com"
              class="input border border-base-300 w-full"
              required
              autocomplete="email"
            />
          </div>

          <!-- Senha -->
          <div class="form-control">
            <label class="label pb-1">
              <span class="label-text font-semibold text-gray-700">Senha</span>
              <a href="#" class="label-text-alt text-brand hover:underline font-medium">
                Esqueceu a senha?
              </a>
            </label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="••••••••"
                class="input border border-base-300 w-full pr-12"
                required
                autocomplete="current-password"
              />
              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                @click="showPassword = !showPassword"
                tabindex="-1"
              >
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Lembrar -->
          <label class="flex items-center gap-3 cursor-pointer">
            <input v-model="form.remember" type="checkbox" class="checkbox checkbox-sm border-brand checked:bg-brand"/>
            <span class="text-sm text-gray-600">Lembrar de mim</span>
          </label>

          <!-- Submit -->
          <button
            type="submit"
            class="btn w-full bg-brand hover:bg-brand-dark text-white border-none font-bold text-base"
            :class="{ loading: loading }"
            :disabled="loading"
          >
            <span v-if="!loading">Entrar</span>
            <span v-else>Entrando...</span>
          </button>
        </form>

        <div class="divider text-gray-400 text-xs my-6">ou</div>

        <p class="text-center text-sm text-gray-500">
          Não tem uma conta?
          <RouterLink to="/register" class="text-brand font-semibold hover:underline ml-1">
            Criar conta grátis
          </RouterLink>
        </p>

        <p class="text-center mt-8 text-xs text-gray-400">
          <RouterLink to="/" class="hover:text-brand transition-colors">← Voltar ao início</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { login } from '@/api/auth.js';

const router = useRouter();

const form = reactive({ email: '', password: '', remember: false });
const loading = ref(false);
const error = ref('');
const showPassword = ref(false);

const features = [
  'Gestão de armazéns e posições',
  'Controle de lotes e validade',
  'Pedidos de compra integrados',
  'Inventário e contagem cíclica',
  'Movimentações com Kardex',
];

async function handleLogin() {
  loading.value = true;
  error.value = '';
  try {
    await login(form.email, form.password, form.remember);
    router.push('/app/dashboard');
  } catch (e) {
    error.value = e.response?.data?.message ?? e.message ?? 'E-mail ou senha incorretos.';
  } finally {
    loading.value = false;
  }
}
</script>
