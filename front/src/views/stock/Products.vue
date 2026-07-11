<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-base-content">Produtos</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openDrawer()">+ Novo Produto</button>
    </div>

    <div class="mb-4">
      <input v-model="search" type="text" placeholder="Buscar por SKU ou nome..." class="input border border-base-300 w-full max-w-sm" @input="debouncedLoad" />
    </div>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>SKU</th><th>Nome</th><th>Un.</th><th>Custo Médio</th><th>Est. Mín.</th><th>Lote</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="8" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="8" class="text-center py-8 text-gray-400">Nenhum produto.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="font-mono text-xs text-gray-500">{{ item.sku }}</td>
            <td class="font-semibold text-base-content">{{ item.name }}</td>
            <td><span class="badge badge-outline badge-sm">{{ item.unit_of_measure }}</span></td>
            <td class="text-sm">R$ {{ Number(item.current_average_cost).toFixed(4) }}</td>
            <td class="text-sm">{{ item.minimum_stock }}</td>
            <td><span class="badge badge-sm" :class="item.tracks_batch ? 'badge-warning' : 'badge-ghost'">{{ item.tracks_batch ? 'Sim' : 'Não' }}</span></td>
            <td><span class="status-badge" :class="item.active ? 'status-ativo' : 'status-inativo'">{{ item.active ? 'Ativo' : 'Inativo' }}</span></td>
            <td class="flex gap-2">
              <button class="btn btn-ghost btn-xs" @click="openDrawer(item)">Editar</button>
              <button class="btn btn-ghost btn-xs text-error" @click="remove(item.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <DrawerPanel v-model="drawerOpen" :title="form.id ? 'Editar Produto' : 'Novo Produto'">
      <div class="grid grid-cols-2 gap-4">
        <fieldset class="fieldset col-span-2">
          <legend class="fieldset-legend">SKU</legend>
          <input v-model="form.sku" type="text" placeholder="Ex: PROD-001" class="input border border-base-300 w-full" />
        </fieldset>
        <fieldset class="fieldset col-span-2">
          <legend class="fieldset-legend">Nome</legend>
          <input v-model="form.name" type="text" class="input border border-base-300 w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Unidade</legend>
          <select v-model="form.unit_of_measure" class="select border border-base-300 w-full">
            <option>UN</option><option>KG</option><option>CX</option><option>LT</option><option>MT</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Método Custo</legend>
          <select v-model="form.costing_method" class="select border border-base-300 w-full">
            <option value="AVERAGE_COST">Custo Médio</option>
            <option value="FIFO">PEPS</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Estoque Mínimo</legend>
          <input v-model="form.minimum_stock" type="number" step="0.001" class="input border border-base-300 w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Estoque Máximo</legend>
          <input v-model="form.maximum_stock" type="number" step="0.001" class="input border border-base-300 w-full" placeholder="Opcional" />
        </fieldset>
        <div class="col-span-2 flex gap-4 pt-1">
          <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input v-model="form.tracks_batch" type="checkbox" class="checkbox checkbox-warning checkbox-sm" /> Controla Lote
          </label>
          <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input v-model="form.active" type="checkbox" class="checkbox checkbox-success checkbox-sm" /> Ativo
          </label>
        </div>
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
import { products as api } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const search = ref(''); const drawerOpen = ref(false);
const emptyForm = { id: null, sku: '', name: '', unit_of_measure: 'UN', costing_method: 'AVERAGE_COST', minimum_stock: 0, maximum_stock: null, tracks_batch: false, active: true };
const form = ref({ ...emptyForm });

let debounceTimer;
function debouncedLoad() { clearTimeout(debounceTimer); debounceTimer = setTimeout(load, 350); }

async function load() {
  loading.value = true;
  try { const r = await api.index(search.value ? { search: search.value } : {}); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
function openDrawer(item = null) { form.value = item ? { ...item } : { ...emptyForm }; drawerOpen.value = true; }
async function save() {
  saving.value = true;
  try { form.value.id ? await api.update(form.value.id, form.value) : await api.store(form.value); drawerOpen.value = false; await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
async function remove(id) { if (!confirm('Excluir este produto?')) return; await api.destroy(id); await load(); }
onMounted(load);
</script>
