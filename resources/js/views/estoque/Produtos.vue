<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-gray-900">Produtos</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openModal()">+ Novo Produto</button>
    </div>

    <!-- Busca -->
    <div class="mb-4">
      <input v-model="search" type="text" placeholder="Buscar por SKU ou nome..." class="input input-bordered w-full max-w-sm" @input="debouncedLoad" />
    </div>

    <div class="card bg-white shadow-sm border border-gray-100 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>SKU</th><th>Nome</th><th>Un.</th><th>Custo Médio</th><th>Est. Mín.</th><th>Lote</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="8" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="8" class="text-center py-8 text-gray-400">Nenhum produto.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="font-mono text-xs text-gray-500">{{ item.sku }}</td>
            <td class="font-semibold text-gray-900">{{ item.nome }}</td>
            <td><span class="badge badge-outline badge-sm">{{ item.unidade_medida }}</span></td>
            <td class="text-sm">R$ {{ Number(item.custo_medio_atual).toFixed(4) }}</td>
            <td class="text-sm">{{ item.estoque_minimo }}</td>
            <td><span class="badge badge-sm" :class="item.controla_lote ? 'badge-warning' : 'badge-ghost'">{{ item.controla_lote ? 'Sim' : 'Não' }}</span></td>
            <td><span class="status-badge" :class="item.ativo ? 'status-ativo' : 'status-inativo'">{{ item.ativo ? 'Ativo' : 'Inativo' }}</span></td>
            <td class="flex gap-2">
              <button class="btn btn-ghost btn-xs" @click="openModal(item)">Editar</button>
              <button class="btn btn-ghost btn-xs text-error" @click="remove(item.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <dialog ref="modalRef" class="modal">
      <div class="modal-box max-w-lg">
        <h3 class="font-black text-lg mb-4">{{ form.id ? 'Editar' : 'Novo' }} Produto</h3>
        <div class="grid grid-cols-2 gap-3">
          <fieldset class="fieldset col-span-2">
            <legend class="fieldset-legend">SKU</legend>
            <input v-model="form.sku" type="text" placeholder="Ex: PROD-001" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset col-span-2">
            <legend class="fieldset-legend">Nome</legend>
            <input v-model="form.nome" type="text" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Unidade</legend>
            <select v-model="form.unidade_medida" class="select select-bordered w-full">
              <option>UN</option><option>KG</option><option>CX</option><option>LT</option><option>MT</option>
            </select>
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Método Custo</legend>
            <select v-model="form.metodo_custo" class="select select-bordered w-full">
              <option value="CUSTO_MEDIO">Custo Médio</option>
              <option value="PEPS">PEPS</option>
            </select>
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Estoque Mínimo</legend>
            <input v-model="form.estoque_minimo" type="number" step="0.001" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Estoque Máximo</legend>
            <input v-model="form.estoque_maximo" type="number" step="0.001" class="input input-bordered w-full" placeholder="Opcional" />
          </fieldset>
          <div class="col-span-2 flex gap-4">
            <label class="flex items-center gap-2 text-sm cursor-pointer">
              <input v-model="form.controla_lote" type="checkbox" class="checkbox checkbox-warning checkbox-sm" /> Controla Lote
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
              <input v-model="form.ativo" type="checkbox" class="checkbox checkbox-success checkbox-sm" /> Ativo
            </label>
          </div>
        </div>
        <div class="modal-action">
          <button class="btn btn-ghost" @click="modalRef.close()">Cancelar</button>
          <button class="btn btn-brand" :disabled="saving" @click="save">{{ saving ? 'Salvando...' : 'Salvar' }}</button>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop"><button>fechar</button></form>
    </dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { produtos as api } from '@/api/estoque.js';

const items    = ref([]);
const loading  = ref(false);
const saving   = ref(false);
const search   = ref('');
const modalRef = ref(null);
const form     = ref({ id: null, sku: '', nome: '', unidade_medida: 'UN', metodo_custo: 'CUSTO_MEDIO', estoque_minimo: 0, estoque_maximo: null, controla_lote: false, ativo: true });

let debounceTimer;
function debouncedLoad() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(load, 350);
}

async function load() {
  loading.value = true;
  try {
    const res = await api.index(search.value ? { search: search.value } : {});
    items.value = res.data.data ?? res.data;
  } catch (e) { console.error(e); }
  finally { loading.value = false; }
}

function openModal(item = null) {
  form.value = item ? { ...item } : { id: null, sku: '', nome: '', unidade_medida: 'UN', metodo_custo: 'CUSTO_MEDIO', estoque_minimo: 0, estoque_maximo: null, controla_lote: false, ativo: true };
  modalRef.value?.showModal();
}

async function save() {
  saving.value = true;
  try {
    form.value.id ? await api.update(form.value.id, form.value) : await api.store(form.value);
    modalRef.value?.close();
    await load();
  } catch (e) { console.error(e); }
  finally { saving.value = false; }
}

async function remove(id) {
  if (!confirm('Excluir este produto?')) return;
  await api.destroy(id);
  await load();
}

onMounted(load);
</script>
