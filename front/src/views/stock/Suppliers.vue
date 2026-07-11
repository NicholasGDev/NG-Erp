<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-base-content">Fornecedores</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openDrawer()">+ Novo Fornecedor</button>
    </div>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>CNPJ</th><th>Razão Social</th><th>E-mail</th><th>Telefone</th><th>Prazo (dias)</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="7" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="7" class="text-center py-8 text-gray-400">Nenhum fornecedor.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="font-mono text-xs">{{ item.tax_id }}</td>
            <td class="font-semibold text-base-content">{{ item.legal_name }}</td>
            <td class="text-sm text-gray-500">{{ item.contact_email ?? '—' }}</td>
            <td class="text-sm text-gray-500">{{ item.phone ?? '—' }}</td>
            <td class="text-sm text-center">{{ item.delivery_lead_time_days }}</td>
            <td><span class="status-badge" :class="item.active ? 'status-ativo' : 'status-inativo'">{{ item.active ? 'Ativo' : 'Inativo' }}</span></td>
            <td class="flex gap-2">
              <button class="btn btn-ghost btn-xs" @click="openDrawer(item)">Editar</button>
              <button class="btn btn-ghost btn-xs text-error" @click="remove(item.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <DrawerPanel v-model="drawerOpen" :title="form.id ? 'Editar Fornecedor' : 'Novo Fornecedor'">
      <div class="grid grid-cols-2 gap-4">
        <fieldset class="fieldset col-span-2">
          <legend class="fieldset-legend">CNPJ</legend>
          <input v-model="form.tax_id" type="text" placeholder="XX.XXX.XXX/XXXX-XX" class="input border border-base-300 w-full" />
        </fieldset>
        <fieldset class="fieldset col-span-2">
          <legend class="fieldset-legend">Razão Social</legend>
          <input v-model="form.legal_name" type="text" class="input border border-base-300 w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">E-mail</legend>
          <input v-model="form.contact_email" type="email" class="input border border-base-300 w-full" placeholder="Opcional" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Telefone</legend>
          <input v-model="form.phone" type="text" class="input border border-base-300 w-full" placeholder="Opcional" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Prazo Entrega (dias)</legend>
          <input v-model="form.delivery_lead_time_days" type="number" min="1" class="input border border-base-300 w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Cond. Pagamento</legend>
          <input v-model="form.default_payment_terms" type="text" placeholder="Ex: 30/60/90" class="input border border-base-300 w-full" />
        </fieldset>
        <label class="col-span-2 flex items-center gap-2 text-sm cursor-pointer">
          <input v-model="form.active" type="checkbox" class="checkbox checkbox-success checkbox-sm" /> Ativo
        </label>
      </div>
      <template #footer>
        <button class="btn btn-ghost" @click="drawerOpen = false">Cancelar</button>
        <button class="btn btn-brand" :disabled="saving" @click="save">{{ saving ? 'Salvando...' : 'Salvar' }}</button>
      </template>
    </DrawerPanel>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { suppliers as api } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const drawerOpen = ref(false);
const emptyForm = { id: null, tax_id: '', legal_name: '', delivery_lead_time_days: 7, default_payment_terms: '', contact_email: '', phone: '', active: true };
const form = ref({ ...emptyForm });

async function load() {
  loading.value = true;
  try { const r = await api.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
function openDrawer(item = null) { form.value = item ? { ...item } : { ...emptyForm }; drawerOpen.value = true; }
async function save() {
  saving.value = true;
  try { form.value.id ? await api.update(form.value.id, form.value) : await api.store(form.value); drawerOpen.value = false; await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
async function remove(id) { if (!confirm('Excluir este fornecedor?')) return; await api.destroy(id); await load(); }
onMounted(load);
</script>
