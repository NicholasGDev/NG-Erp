<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-gray-900">Movimentações de Estoque <span class="text-sm font-normal text-gray-400">(Kardex)</span></h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openDrawer()">+ Nova Movimentação</button>
    </div>

    <div class="card bg-white shadow-sm border border-gray-100 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>Data</th><th>Produto</th><th>Tipo</th><th>Quantidade</th><th>Custo Unit.</th><th>Saldo Após</th><th>Doc. Ref.</th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="7" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="7" class="text-center py-8 text-gray-400">Sem movimentações.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="text-sm text-gray-500 whitespace-nowrap">{{ fmtDate(item.occurred_at) }}</td>
            <td class="font-semibold text-gray-900 text-sm">{{ item.product?.name ?? item.product_id }}</td>
            <td><span class="badge badge-outline badge-sm text-xs">{{ item.movement_type }}</span></td>
            <td class="text-sm font-mono">{{ item.quantity }}</td>
            <td class="text-sm font-mono">R$ {{ Number(item.movement_unit_cost).toFixed(4) }}</td>
            <td class="text-sm font-mono font-semibold" :class="item.balance_after_movement > 0 ? 'text-green-600' : 'text-red-500'">{{ item.balance_after_movement }}</td>
            <td class="text-xs text-gray-400">{{ item.reference_document ?? '—' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <DrawerPanel v-model="drawerOpen" title="Nova Movimentação">
      <div class="space-y-4">
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Produto</legend>
          <select v-model="form.product_id" class="select select-bordered w-full">
            <option disabled value="">Selecione...</option>
            <option v-for="p in productsList" :key="p.id" :value="p.id">{{ p.sku }} — {{ p.name }}</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Tipo de Movimento</legend>
          <select v-model="form.movement_type" class="select select-bordered w-full">
            <option value="purchase_in">Entrada Compra</option>
            <option value="sale_out">Saída Venda</option>
            <option value="transfer">Transferência</option>
            <option value="loss_adjustment">Ajuste Perda</option>
            <option value="gain_adjustment">Ajuste Ganho</option>
            <option value="return">Devolução</option>
          </select>
        </fieldset>
        <div class="grid grid-cols-2 gap-3">
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Quantidade</legend>
            <input v-model="form.quantity" type="number" step="0.001" min="0.001" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Custo Unitário</legend>
            <input v-model="form.movement_unit_cost" type="number" step="0.0001" min="0" class="input input-bordered w-full" />
          </fieldset>
        </div>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Doc. Referência</legend>
          <input v-model="form.reference_document" type="text" placeholder="NF, pedido, etc." class="input input-bordered w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Observação</legend>
          <textarea v-model="form.notes" class="textarea textarea-bordered w-full" rows="3" />
        </fieldset>
      </div>
      <template #footer>
        <button class="btn btn-ghost" @click="drawerOpen = false">Cancelar</button>
        <button class="btn btn-brand" :disabled="saving" @click="save">{{ saving ? 'Salvando...' : 'Registrar' }}</button>
      </template>
    </DrawerPanel>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { stockMovements as api, products as prodApi } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const productsList = ref([]); const drawerOpen = ref(false);
const emptyForm = { product_id: '', movement_type: 'purchase_in', quantity: 1, movement_unit_cost: 0, reference_document: '', notes: '' };
const form = ref({ ...emptyForm });

const fmtDate = (d) => d ? new Date(d).toLocaleString('pt-BR') : '—';

async function load() {
  loading.value = true;
  try { const r = await api.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
async function loadProducts() {
  try { const r = await prodApi.index(); productsList.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); }
}
function openDrawer() { form.value = { ...emptyForm }; drawerOpen.value = true; }
async function save() {
  saving.value = true;
  try { await api.store(form.value); drawerOpen.value = false; await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
onMounted(() => { load(); loadProducts(); });
</script>
