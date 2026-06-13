<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-gray-900">Inventários</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openDrawer()">+ Novo Inventário</button>
    </div>

    <div class="card bg-white shadow-sm border border-gray-100 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>#</th><th>Armazém</th><th>Início</th><th>Fim</th><th>Status</th><th>Responsável</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="7" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="7" class="text-center py-8 text-gray-400">Sem inventários.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="text-gray-400 text-sm">{{ item.id }}</td>
            <td class="font-semibold text-gray-900">{{ item.armazem?.nome ?? item.armazem_id }}</td>
            <td class="text-sm">{{ fmtDate(item.data_inicio) }}</td>
            <td class="text-sm text-gray-500">{{ item.data_fim ? fmtDate(item.data_fim) : '—' }}</td>
            <td><span class="status-badge" :class="`status-${item.status === 'em_andamento' ? 'emitido' : item.status === 'ajustado' ? 'concluido' : 'cancelado'}`">{{ item.status }}</span></td>
            <td class="text-sm text-gray-500">{{ item.usuario_responsavel_id }}</td>
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
          <select v-model="form.armazem_id" class="select select-bordered w-full">
            <option disabled value="">Selecione...</option>
            <option v-for="a in armazensList" :key="a.id" :value="a.id">{{ a.nome }}</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Data Início</legend>
          <input v-model="form.data_inicio" type="datetime-local" class="input input-bordered w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Data Fim</legend>
          <input v-model="form.data_fim" type="datetime-local" class="input input-bordered w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Status</legend>
          <select v-model="form.status" class="select select-bordered w-full">
            <option value="em_andamento">Em Andamento</option>
            <option value="ajustado">Ajustado</option>
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
import { inventarios as api, armazens as armApi } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const armazensList = ref([]); const drawerOpen = ref(false);
const now = new Date().toISOString().slice(0, 16);
const emptyForm = { id: null, armazem_id: '', data_inicio: now, data_fim: '', status: 'em_andamento', observacoes: '', usuario_responsavel_id: 1 };
const form = ref({ ...emptyForm });

const fmtDate = (d) => d ? new Date(d).toLocaleString('pt-BR') : '—';

async function load() {
  loading.value = true;
  try { const r = await api.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
async function loadArmazens() {
  try { const r = await armApi.index(); armazensList.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); }
}
function openDrawer(item = null) {
  form.value = item ? { ...item, data_inicio: item.data_inicio?.slice(0,16), data_fim: item.data_fim?.slice(0,16) ?? '' } : { ...emptyForm };
  drawerOpen.value = true;
}
async function save() {
  saving.value = true;
  try { form.value.id ? await api.update(form.value.id, form.value) : await api.store(form.value); drawerOpen.value = false; await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
async function remove(id) { if (!confirm('Excluir inventário?')) return; await api.destroy(id); await load(); }
onMounted(() => { load(); loadArmazens(); });
</script>
