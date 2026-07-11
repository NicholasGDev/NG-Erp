<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-gray-900">Pedidos de Compra</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openDrawer()">+ Novo Pedido</button>
    </div>

    <div class="card bg-white shadow-sm border border-gray-100 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>Nº Pedido</th><th>Fornecedor</th><th>Emissão</th><th>Entrega Prevista</th><th>Valor Total</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="7" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="7" class="text-center py-8 text-gray-400">Nenhum pedido.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="font-mono text-xs text-gray-500">{{ item.order_number ?? `#${item.id}` }}</td>
            <td class="font-semibold text-gray-900">{{ item.supplier?.legal_name ?? item.supplier_id }}</td>
            <td class="text-sm">{{ fmtDate(item.issued_at) }}</td>
            <td class="text-sm text-gray-500">{{ item.expected_delivery_date ? fmtDate(item.expected_delivery_date) : '—' }}</td>
            <td class="text-sm font-semibold">R$ {{ Number(item.total_amount).toFixed(2) }}</td>
            <td><span class="status-badge" :class="`status-${item.status}`">{{ item.status }}</span></td>
            <td class="flex gap-2">
              <button class="btn btn-ghost btn-xs" @click="openDrawer(item)">Editar</button>
              <button class="btn btn-ghost btn-xs text-error" @click="remove(item.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <DrawerPanel v-model="drawerOpen" :title="form.id ? 'Editar Pedido' : 'Novo Pedido'">
      <div class="space-y-4">
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Fornecedor</legend>
          <select v-model="form.supplier_id" class="select select-bordered w-full">
            <option disabled value="">Selecione...</option>
            <option v-for="f in suppliersList" :key="f.id" :value="f.id">{{ f.legal_name }}</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Nº Pedido</legend>
          <input v-model="form.order_number" type="text" placeholder="Automático se vazio" class="input input-bordered w-full" />
        </fieldset>
        <div class="grid grid-cols-2 gap-3">
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Data Emissão</legend>
            <input v-model="form.issued_at" type="datetime-local" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Entrega Prevista</legend>
            <input v-model="form.expected_delivery_date" type="date" class="input input-bordered w-full" />
          </fieldset>
        </div>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Status</legend>
          <select v-model="form.status" class="select select-bordered w-full">
            <option value="draft">Rascunho</option>
            <option value="issued">Emitido</option>
            <option value="partially_received">Recebido Parcial</option>
            <option value="completed">Concluído</option>
            <option value="cancelled">Cancelado</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Observações</legend>
          <textarea v-model="form.notes" class="textarea textarea-bordered w-full" rows="3" />
        </fieldset>
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
import { purchaseOrders as api, suppliers as suppApi } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const suppliersList = ref([]); const drawerOpen = ref(false);
const now = new Date().toISOString().slice(0, 16);
const emptyForm = { id: null, supplier_id: '', order_number: '', issued_at: now, expected_delivery_date: '', status: 'draft', notes: '' };
const form = ref({ ...emptyForm });

const fmtDate = (d) => d ? new Date(d).toLocaleDateString('pt-BR') : '—';

async function load() {
  loading.value = true;
  try { const r = await api.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
async function loadSuppliers() {
  try { const r = await suppApi.index(); suppliersList.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); }
}
function openDrawer(item = null) { form.value = item ? { ...item, issued_at: item.issued_at?.slice(0,16) } : { ...emptyForm }; drawerOpen.value = true; }
async function save() {
  saving.value = true;
  try { form.value.id ? await api.update(form.value.id, form.value) : await api.store(form.value); drawerOpen.value = false; await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
async function remove(id) { if (!confirm('Excluir pedido?')) return; await api.destroy(id); await load(); }
onMounted(() => { load(); loadSuppliers(); });
</script>
