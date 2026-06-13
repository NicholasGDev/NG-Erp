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
            <td class="text-sm text-gray-500 whitespace-nowrap">{{ fmtDate(item.data_hora) }}</td>
            <td class="font-semibold text-gray-900 text-sm">{{ item.produto?.nome ?? item.produto_id }}</td>
            <td><span class="badge badge-outline badge-sm text-xs">{{ item.tipo_movimento }}</span></td>
            <td class="text-sm font-mono">{{ item.quantidade }}</td>
            <td class="text-sm font-mono">R$ {{ Number(item.custo_unitario_movimento).toFixed(4) }}</td>
            <td class="text-sm font-mono font-semibold" :class="item.saldo_apos_movimento > 0 ? 'text-green-600' : 'text-red-500'">{{ item.saldo_apos_movimento }}</td>
            <td class="text-xs text-gray-400">{{ item.documento_referencia ?? '—' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <DrawerPanel v-model="drawerOpen" title="Nova Movimentação">
      <div class="space-y-4">
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Produto</legend>
          <select v-model="form.produto_id" class="select select-bordered w-full">
            <option disabled value="">Selecione...</option>
            <option v-for="p in produtosList" :key="p.id" :value="p.id">{{ p.sku }} — {{ p.nome }}</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Tipo de Movimento</legend>
          <select v-model="form.tipo_movimento" class="select select-bordered w-full">
            <option value="entrada_compra">Entrada Compra</option>
            <option value="saida_venda">Saída Venda</option>
            <option value="transferencia">Transferência</option>
            <option value="ajuste_perda">Ajuste Perda</option>
            <option value="ajuste_ganho">Ajuste Ganho</option>
            <option value="devolucao">Devolução</option>
          </select>
        </fieldset>
        <div class="grid grid-cols-2 gap-3">
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Quantidade</legend>
            <input v-model="form.quantidade" type="number" step="0.001" min="0.001" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Custo Unitário</legend>
            <input v-model="form.custo_unitario_movimento" type="number" step="0.0001" min="0" class="input input-bordered w-full" />
          </fieldset>
        </div>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Saldo Após Movimento</legend>
          <input v-model="form.saldo_apos_movimento" type="number" step="0.001" class="input input-bordered w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Data/Hora</legend>
          <input v-model="form.data_hora" type="datetime-local" class="input input-bordered w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Doc. Referência</legend>
          <input v-model="form.documento_referencia" type="text" placeholder="NF, pedido, etc." class="input input-bordered w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Observação</legend>
          <textarea v-model="form.observacao" class="textarea textarea-bordered w-full" rows="3" />
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
import { movimentacoes as api, produtos as prodApi } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const produtosList = ref([]); const drawerOpen = ref(false);
const now = new Date().toISOString().slice(0, 16);
const emptyForm = { produto_id: '', tipo_movimento: 'entrada_compra', quantidade: 1, custo_unitario_movimento: 0, saldo_apos_movimento: 0, data_hora: now, documento_referencia: '', observacao: '', usuario_id: 1 };
const form = ref({ ...emptyForm });

const fmtDate = (d) => d ? new Date(d).toLocaleString('pt-BR') : '—';

async function load() {
  loading.value = true;
  try { const r = await api.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
async function loadProdutos() {
  try { const r = await prodApi.index(); produtosList.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); }
}
function openDrawer() { form.value = { ...emptyForm, data_hora: new Date().toISOString().slice(0, 16) }; drawerOpen.value = true; }
async function save() {
  saving.value = true;
  try { await api.store(form.value); drawerOpen.value = false; await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
onMounted(() => { load(); loadProdutos(); });
</script>
