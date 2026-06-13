<template>
  <div class="min-h-screen flex">

    <!-- Painel esquerdo — branding -->
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-brand relative overflow-hidden">
      <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 pointer-events-none"></div>
      <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full bg-white/10 pointer-events-none"></div>

      <RouterLink to="/" class="text-white text-2xl font-black tracking-tight z-10">
        ngERP
      </RouterLink>

      <div class="z-10">
        <h2 class="text-white text-4xl font-black leading-tight mb-4">
          Comece agora,<br>é grátis por 30 dias.
        </h2>
        <p class="text-green-100 text-lg mb-8">
          Sem cartão de crédito. Configure em minutos e já comece a controlar seu estoque.
        </p>

        <div class="grid grid-cols-2 gap-4">
          <div v-for="stat in stats" :key="stat.label" class="bg-white/10 rounded-2xl p-4">
            <p class="text-white text-2xl font-black">{{ stat.value }}</p>
            <p class="text-green-100 text-xs mt-1">{{ stat.label }}</p>
          </div>
        </div>
      </div>

      <p class="text-green-200 text-xs z-10">© 2026 ngERP · Todos os direitos reservados</p>
    </div>

    <!-- Painel direito — formulário -->
    <div class="flex-1 flex items-center justify-center p-6 bg-white overflow-y-auto">
      <div class="w-full max-w-md py-8">

        <!-- Logo mobile -->
        <RouterLink to="/" class="lg:hidden block text-brand text-2xl font-black mb-8 text-center">
          ngERP
        </RouterLink>

        <div class="mb-8">
          <h1 class="text-2xl font-black text-gray-900 mb-1">Criar sua conta</h1>
          <p class="text-gray-500 text-sm">Preencha os dados abaixo para começar</p>
        </div>

        <!-- Alerta de erro -->
        <div v-if="error" class="alert alert-error mb-6 text-sm py-3">
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/>
          </svg>
          {{ error }}
        </div>

        <!-- Sucesso -->
        <div v-if="success" class="alert alert-success mb-6 text-sm py-3">
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Conta criada! Redirecionando...
        </div>

        <form @submit.prevent="handleRegister" class="space-y-4">
          <!-- Nome completo -->
          <div class="form-control">
            <label class="label pb-1">
              <span class="label-text font-semibold text-gray-700">Nome completo</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              placeholder="João da Silva"
              class="input input-bordered w-full focus:border-brand focus:outline-none"
              required
              autocomplete="name"
            />
          </div>

          <!-- Empresa -->
          <div class="form-control">
            <label class="label pb-1">
              <span class="label-text font-semibold text-gray-700">Nome da empresa</span>
              <span class="label-text-alt text-gray-400">Opcional</span>
            </label>
            <input
              v-model="form.company"
              type="text"
              placeholder="Minha Empresa Ltda"
              class="input input-bordered w-full focus:border-brand focus:outline-none"
              autocomplete="organization"
            />
          </div>

          <!-- E-mail -->
          <div class="form-control">
            <label class="label pb-1">
              <span class="label-text font-semibold text-gray-700">E-mail</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder="seu@email.com"
              class="input input-bordered w-full focus:border-brand focus:outline-none"
              required
              autocomplete="email"
            />
            <label v-if="fieldErrors.email" class="label pt-1">
              <span class="label-text-alt text-error">{{ fieldErrors.email }}</span>
            </label>
          </div>

          <!-- Senha -->
          <div class="form-control">
            <label class="label pb-1">
              <span class="label-text font-semibold text-gray-700">Senha</span>
            </label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Mínimo 8 caracteres"
                class="input input-bordered w-full pr-12 focus:border-brand focus:outline-none"
                required
                minlength="8"
                autocomplete="new-password"
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
            <!-- Força da senha -->
            <div class="mt-2 flex gap-1">
              <div
                v-for="i in 4" :key="i"
                class="h-1 flex-1 rounded-full transition-colors"
                :class="passwordStrength >= i ? strengthColor : 'bg-gray-200'"
              ></div>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ strengthLabel }}</p>
          </div>

          <!-- Confirmar senha -->
          <div class="form-control">
            <label class="label pb-1">
              <span class="label-text font-semibold text-gray-700">Confirmar senha</span>
            </label>
            <input
              v-model="form.passwordConfirmation"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Repita a senha"
              class="input input-bordered w-full focus:border-brand focus:outline-none"
              :class="{ 'input-error': form.passwordConfirmation && form.password !== form.passwordConfirmation }"
              required
              autocomplete="new-password"
            />
            <label v-if="form.passwordConfirmation && form.password !== form.passwordConfirmation" class="label pt-1">
              <span class="label-text-alt text-error">As senhas não coincidem</span>
            </label>
          </div>

          <!-- Termos -->
          <label class="flex items-start gap-3 cursor-pointer mt-2">
            <input v-model="form.terms" type="checkbox" class="checkbox checkbox-sm border-brand checked:bg-brand mt-0.5" required/>
            <span class="text-sm text-gray-600 leading-relaxed">
              Li e aceito os
              <a href="#" class="text-brand font-semibold hover:underline">Termos de Uso</a>
              e a
              <a href="#" class="text-brand font-semibold hover:underline">Política de Privacidade</a>
            </span>
          </label>

          <!-- Submit -->
          <button
            type="submit"
            class="btn w-full bg-brand hover:bg-brand-dark text-white border-none font-bold text-base mt-2"
            :class="{ loading: loading }"
            :disabled="loading || (form.passwordConfirmation && form.password !== form.passwordConfirmation)"
          >
            <span v-if="!loading">Criar conta grátis</span>
            <span v-else>Criando conta...</span>
          </button>
        </form>

        <div class="divider text-gray-400 text-xs my-6">ou</div>

        <p class="text-center text-sm text-gray-500">
          Já tem uma conta?
          <RouterLink to="/login" class="text-brand font-semibold hover:underline ml-1">
            Entrar
          </RouterLink>
        </p>

        <p class="text-center mt-6 text-xs text-gray-400">
          <RouterLink to="/" class="hover:text-brand transition-colors">← Voltar ao início</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { register } from '@/api/auth.js';

const router = useRouter();

const form = reactive({
  name: '',
  company: '',
  email: '',
  password: '',
  passwordConfirmation: '',
  terms: false,
});

const loading = ref(false);
const error = ref('');
const success = ref(false);
const showPassword = ref(false);
const fieldErrors = reactive({});

const stats = [
  { value: '+80k', label: 'Empresas cadastradas' },
  { value: '99,9%', label: 'Uptime garantido' },
  { value: 'R$ 0', label: 'Para começar' },
  { value: '30 dias', label: 'Grátis sem cartão' },
];

const passwordStrength = computed(() => {
  const p = form.password;
  if (!p) return 0;
  let score = 0;
  if (p.length >= 8) score++;
  if (/[A-Z]/.test(p)) score++;
  if (/[0-9]/.test(p)) score++;
  if (/[^A-Za-z0-9]/.test(p)) score++;
  return score;
});

const strengthColor = computed(() => {
  const colors = ['', 'bg-error', 'bg-warning', 'bg-info', 'bg-brand'];
  return colors[passwordStrength.value] || 'bg-gray-200';
});

const strengthLabel = computed(() => {
  const labels = ['', 'Muito fraca', 'Fraca', 'Boa', 'Forte'];
  return form.password ? labels[passwordStrength.value] : '';
});

async function handleRegister() {
  if (form.password !== form.passwordConfirmation) return;
  loading.value = true;
  error.value = '';
  Object.keys(fieldErrors).forEach(k => delete fieldErrors[k]);

  try {
    await register({
      name:                  form.name,
      company:               form.company,
      email:                 form.email,
      password:              form.password,
      password_confirmation: form.passwordConfirmation,
    });
    success.value = true;
    setTimeout(() => router.push('/app/dashboard'), 1200);
  } catch (e) {
    if (e.errors) {
      Object.assign(fieldErrors, e.errors);
      error.value = 'Corrija os campos destacados.';
    } else {
      error.value = e.response?.data?.message ?? e.message ?? 'Erro ao criar conta. Tente novamente.';
    }
  } finally {
    loading.value = false;
  }
}
</script>
