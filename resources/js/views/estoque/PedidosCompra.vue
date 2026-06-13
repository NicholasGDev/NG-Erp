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
            <td class="font-mono text-xs text-gray-500">{{ item.numero_pedido ?? `#${item.id}` }}</td>
            <td class="font-semibold text-gray-900">{{ item.fornecedor?.razao_social ?? item.fornecedor_id }}</td>
            <td class="text-sm">{{ fmtDate(item.data_emissao) }}</td>
            <td class="text-sm text-gray-500">{{ item.data_prevista_entrega ? fmtDate(item.data_prevista_entrega) : '—' }}</td>
            <td class="text-sm font-semibold">R$ {{ Number(item.valor_total).toFixed(2) }}</td>
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
          <select v-model="form.fornecedor_id" class="select select-bordered w-full">
            <option disabled value="">Selecione...</option>
            <option v-for="f in fornecedoresList" :key="f.id" :value="f.id">{{ f.razao_social }}</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Nº Pedido</legend>
          <input v-model="form.numero_pedido" type="text" placeholder="Automático se vazio" class="input input-bordered w-full" />
        </fieldset>
        <div class="grid grid-cols-2 gap-3">
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Data Emissão</legend>
            <input v-model="form.data_emissao" type="datetime-local" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Entrega Prevista</legend>
            <input v-model="form.data_prevista_entrega" type="date" class="input input-bordered w-full" />
          </fieldset>
        </div>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Status</legend>
          <select v-model="form.status" class="select select-bordered w-full">
            <option value="rascunho">Rascunho</option>
            <option value="emitido">Emitido</option>
            <option value="recebido_parcial">Recebido Parcial</option>
            <option value="concluido">Concluído</option>
            <option value="cancelado">Cancelado</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Observações</legend>
          <textarea v-model="form.observacoes" class="textarea textarea-bordered w-full" rows="3" />
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
import { pedidosCompra as api, fornecedores as fornApi } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const fornecedoresList = ref([]); const drawerOpen = ref(false);
const now = new Date().toISOString().slice(0, 16);
const emptyForm = { id: null, fornecedor_id: '', numero_pedido: '', data_emissao: now, data_prevista_entrega: '', status: 'rascunho', observacoes: '' };
const form = ref({ ...emptyForm });

const fmtDate = (d) => d ? new Date(d).toLocaleDateString('pt-BR') : '—';

async function load() {
  loading.value = true;
  try { const r = await api.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
async function loadFornecedores() {
  try { const r = await fornApi.index(); fornecedoresList.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); }
}
function openDrawer(item = null) { form.value = item ? { ...item, data_emissao: item.data_emissao?.slice(0,16) } : { ...emptyForm }; drawerOpen.value = true; }
async function save() {
  saving.value = true;
  try { form.value.id ? await api.update(form.value.id, form.value) : await api.store(form.value); drawerOpen.value = false; await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
async function remove(id) { if (!confirm('Excluir pedido?')) return; await api.destroy(id); await load(); }
onMounted(() => { load(); loadFornecedores(); });
</script>
