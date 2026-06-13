<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-gray-900">Fornecedores</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openModal()">+ Novo Fornecedor</button>
    </div>

    <div class="card bg-white shadow-sm border border-gray-100 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>CNPJ</th><th>Razão Social</th><th>E-mail</th><th>Telefone</th><th>Prazo (dias)</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="7" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="7" class="text-center py-8 text-gray-400">Nenhum fornecedor.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="font-mono text-xs">{{ item.cnpj }}</td>
            <td class="font-semibold text-gray-900">{{ item.razao_social }}</td>
            <td class="text-sm text-gray-500">{{ item.email_contato ?? '—' }}</td>
            <td class="text-sm text-gray-500">{{ item.telefone ?? '—' }}</td>
            <td class="text-sm text-center">{{ item.prazo_entrega_dias }}</td>
            <td><span class="status-badge" :class="item.ativo ? 'status-ativo' : 'status-inativo'">{{ item.ativo ? 'Ativo' : 'Inativo' }}</span></td>
            <td class="flex gap-2">
              <button class="btn btn-ghost btn-xs" @click="openModal(item)">Editar</button>
              <button class="btn btn-ghost btn-xs text-error" @click="remove(item.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <dialog ref="modalRef" class="modal">
      <div class="modal-box max-w-lg">
        <h3 class="font-black text-lg mb-4">{{ form.id ? 'Editar' : 'Novo' }} Fornecedor</h3>
        <div class="grid grid-cols-2 gap-3">
          <fieldset class="fieldset col-span-2">
            <legend class="fieldset-legend">CNPJ</legend>
            <input v-model="form.cnpj" type="text" placeholder="XX.XXX.XXX/XXXX-XX" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset col-span-2">
            <legend class="fieldset-legend">Razão Social</legend>
            <input v-model="form.razao_social" type="text" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">E-mail</legend>
            <input v-model="form.email_contato" type="email" class="input input-bordered w-full" placeholder="Opcional" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Telefone</legend>
            <input v-model="form.telefone" type="text" class="input input-bordered w-full" placeholder="Opcional" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Prazo Entrega (dias)</legend>
            <input v-model="form.prazo_entrega_dias" type="number" min="1" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Cond. Pagamento</legend>
            <input v-model="form.condicao_pagamento_padrao" type="text" placeholder="Ex: 30/60/90" class="input input-bordered w-full" />
          </fieldset>
          <label class="col-span-2 flex items-center gap-2 text-sm cursor-pointer">
            <input v-model="form.ativo" type="checkbox" class="checkbox checkbox-success checkbox-sm" /> Ativo
          </label>
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
import { fornecedores as api } from '@/api/estoque.js';

const items = ref([]); const loading = ref(false); const saving = ref(false);
const modalRef = ref(null);
const emptyForm = { id: null, cnpj: '', razao_social: '', prazo_entrega_dias: 7, condicao_pagamento_padrao: '', email_contato: '', telefone: '', ativo: true };
const form = ref({ ...emptyForm });

async function load() {
  loading.value = true;
  try { const r = await api.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); } finally { loading.value = false; }
}
function openModal(item = null) { form.value = item ? { ...item } : { ...emptyForm }; modalRef.value?.showModal(); }
async function save() {
  saving.value = true;
  try { form.value.id ? await api.update(form.value.id, form.value) : await api.store(form.value); modalRef.value?.close(); await load(); }
  catch (e) { console.error(e); } finally { saving.value = false; }
}
async function remove(id) { if (!confirm('Excluir fornecedor?')) return; await api.destroy(id); await load(); }
onMounted(load);
</script>
