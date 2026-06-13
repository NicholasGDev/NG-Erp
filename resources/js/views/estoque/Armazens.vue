<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-gray-900">Armazéns</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openModal()">+ Novo Armazém</button>
    </div>

    <!-- Tabela -->
    <div class="card bg-white shadow-sm border border-gray-100 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr>
            <th>#</th><th>Nome</th><th>Tipo</th><th>Endereço</th><th>Status</th><th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="6" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="6" class="text-center py-8 text-gray-400">Nenhum armazém cadastrado.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="text-gray-400 text-sm">{{ item.id }}</td>
            <td class="font-semibold text-gray-900">{{ item.nome }}</td>
            <td><span class="badge badge-outline badge-sm">{{ item.tipo }}</span></td>
            <td class="text-sm text-gray-500">{{ item.endereco ?? '—' }}</td>
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
      <div class="modal-box">
        <h3 class="font-black text-lg mb-4">{{ form.id ? 'Editar' : 'Novo' }} Armazém</h3>
        <div class="space-y-3">
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Nome</legend>
            <input v-model="form.nome" type="text" placeholder="Ex: Depósito Central" class="input input-bordered w-full" />
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Tipo</legend>
            <select v-model="form.tipo" class="select select-bordered w-full">
              <option value="loja">Loja</option>
              <option value="deposito_central">Depósito Central</option>
              <option value="terceiros">Terceiros</option>
            </select>
          </fieldset>
          <fieldset class="fieldset">
            <legend class="fieldset-legend">Endereço</legend>
            <textarea v-model="form.endereco" class="textarea textarea-bordered w-full" rows="2" placeholder="Opcional" />
          </fieldset>
          <div class="flex items-center gap-2">
            <input v-model="form.ativo" type="checkbox" class="checkbox checkbox-success" id="ativo" />
            <label for="ativo" class="text-sm font-medium">Ativo</label>
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
import { armazens } from '@/api/estoque.js';

const items   = ref([]);
const loading = ref(false);
const saving  = ref(false);
const modalRef = ref(null);
const form = ref({ id: null, nome: '', tipo: 'loja', endereco: '', ativo: true });

async function load() {
  loading.value = true;
  try { items.value = (await armazens.index()).data.data ?? (await armazens.index()).data; }
  catch (e) { console.error(e); }
  finally { loading.value = false; }
}

function openModal(item = null) {
  form.value = item
    ? { ...item }
    : { id: null, nome: '', tipo: 'loja', endereco: '', ativo: true };
  modalRef.value?.showModal();
}

async function save() {
  saving.value = true;
  try {
    form.value.id
      ? await armazens.update(form.value.id, form.value)
      : await armazens.store(form.value);
    modalRef.value?.close();
    await load();
  } catch (e) { console.error(e); }
  finally { saving.value = false; }
}

async function remove(id) {
  if (!confirm('Excluir este armazém?')) return;
  await armazens.destroy(id);
  await load();
}

onMounted(load);
</script>
