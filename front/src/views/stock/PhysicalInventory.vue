<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-base-content">Inventários</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openDrawer()">+ Novo Inventário</button>
    </div>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>#</th><th>Armazém</th><th>Início</th><th>Fim</th><th>Status</th><th>Responsável</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="7" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="7" class="text-center py-8 text-gray-400">Sem inventários.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="text-gray-400 text-sm">{{ item.id }}</td>
            <td class="font-semibold text-base-content">{{ item.warehouse?.name ?? item.warehouse_id }}</td>
            <td class="text-sm">{{ fmtDate(item.started_at) }}</td>
            <td class="text-sm text-gray-500">{{ item.finished_at ? fmtDate(item.finished_at) : '—' }}</td>
            <td><span class="status-badge" :class="`status-${item.status === 'in_progress' ? 'emitido' : item.status === 'adjusted' ? 'concluido' : 'cancelado'}`">{{ item.status }}</span></td>
            <td class="text-sm text-gray-500">{{ item.responsible_user_id }}</td>
            <td class="flex gap-2">
              <button class="btn btn-ghost btn-xs" @click="openDrawer(item)">Editar</button>
              <button class="btn btn-ghost btn-xs text-error" @click="remove(item.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <DrawerPanel v-model="drawerOpen" :title="form.id ? 'Editar Inventário' : 'Novo Inventário'">
      <div class="space-y-4">
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Armazém</legend>
          <select v-model="form.warehouse_id" class="select border border-base-300 w-full">
            <option disabled value="">Selecione...</option>
            <option v-for="w in warehousesList" :key="w.id" :value="w.id">{{ w.name }}</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Data Início</legend>
          <input v-model="form.started_at" type="datetime-local" class="input border border-base-300 w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Data Fim</legend>
          <input v-model="form.finished_at" type="datetime-local" class="input border border-base-300 w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Status</legend>
          <select v-model="form.status" class="select border border-base-300 w-full">
            <option value="in_progress">Em Andamento</option>
            <option value="adjusted">Ajustado</option>
            <option value="cancelled">Cancelado</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Observações</legend>
          <textarea v-model="form.notes" class="textarea border border-base-300 w-full" rows="3" />
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
import { physicalInventories as api, warehouses as whApi } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const warehousesList = ref([]); const drawerOpen = ref(false);
const now = new Date().toISOString().slice(0, 16);
const emptyForm = { id: null, warehouse_id: '', started_at: now, finished_at: '', status: 'in_progress', notes: '' };
const form = ref({ ...emptyForm });

const fmtDate = (d) => d ? new Date(d).toLocaleString('pt-BR') : '—';

async function load() {
  loading.value = true;
  try { const r = await api.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
async function loadWarehouses() {
  try { const r = await whApi.index(); warehousesList.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); }
}
function openDrawer(item = null) {
  form.value = item ? { ...item, started_at: item.started_at?.slice(0,16), finished_at: item.finished_at?.slice(0,16) ?? '' } : { ...emptyForm };
  drawerOpen.value = true;
}
async function save() {
  saving.value = true;
  try { form.value.id ? await api.update(form.value.id, form.value) : await api.store(form.value); drawerOpen.value = false; await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
async function remove(id) { if (!confirm('Excluir inventário?')) return; await api.destroy(id); await load(); }
onMounted(() => { load(); loadWarehouses(); });
</script>
