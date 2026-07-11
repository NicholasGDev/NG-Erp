<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-black text-gray-900">Armazéns</h2>
      <button class="btn btn-brand btn-sm rounded-full" @click="openDrawer()">+ Novo Armazém</button>
    </div>

    <div class="card bg-white shadow-sm border border-gray-100 overflow-x-auto">
      <table class="table erp-table w-full">
        <thead>
          <tr><th>#</th><th>Nome</th><th>Tipo</th><th>Endereço</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="6" class="text-center py-8 text-gray-400">Carregando...</td></tr>
          <tr v-else-if="!items.length"><td colspan="6" class="text-center py-8 text-gray-400">Nenhum armazém cadastrado.</td></tr>
          <tr v-for="item in items" :key="item.id">
            <td class="text-gray-400 text-sm">{{ item.id }}</td>
            <td class="font-semibold text-gray-900">{{ item.name }}</td>
            <td><span class="badge badge-outline badge-sm">{{ item.type }}</span></td>
            <td class="text-sm text-gray-500">{{ item.address ?? '—' }}</td>
            <td><span class="status-badge" :class="item.active ? 'status-ativo' : 'status-inativo'">{{ item.active ? 'Ativo' : 'Inativo' }}</span></td>
            <td class="flex gap-2">
              <button class="btn btn-ghost btn-xs" @click="openDrawer(item)">Editar</button>
              <button class="btn btn-ghost btn-xs text-error" @click="remove(item.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <DrawerPanel v-model="drawerOpen" :title="form.id ? 'Editar Armazém' : 'Novo Armazém'">
      <div class="space-y-4">
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Nome</legend>
          <input v-model="form.name" type="text" placeholder="Ex: Depósito Central" class="input input-bordered w-full" />
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Tipo</legend>
          <select v-model="form.type" class="select select-bordered w-full">
            <option value="store">Loja</option>
            <option value="central_warehouse">Depósito Central</option>
            <option value="third_party">Terceiros</option>
          </select>
        </fieldset>
        <fieldset class="fieldset">
          <legend class="fieldset-legend">Endereço</legend>
          <textarea v-model="form.address" class="textarea textarea-bordered w-full" rows="3" placeholder="Opcional" />
        </fieldset>
        <label class="flex items-center gap-2 text-sm cursor-pointer">
          <input v-model="form.active" type="checkbox" class="checkbox checkbox-success checkbox-sm" /> Ativo
        </label>
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
import { warehouses } from '@/api/estoque.js';
import DrawerPanel from '@/components/DrawerPanel.vue';

const items    = ref([]);
const loading  = ref(false);
const saving   = ref(false);
const drawerOpen = ref(false);
const emptyForm = { id: null, name: '', type: 'store', address: '', active: true };
const form = ref({ ...emptyForm });

async function load() {
  loading.value = true;
  try { const r = await warehouses.index(); items.value = r.data.data ?? r.data; }
  catch (e) { console.error(e); }
  finally { loading.value = false; }
}

function openDrawer(item = null) {
  form.value = item ? { ...item } : { ...emptyForm };
  drawerOpen.value = true;
}

async function save() {
  saving.value = true;
  try {
    form.value.id ? await warehouses.update(form.value.id, form.value) : await warehouses.store(form.value);
    drawerOpen.value = false;
    await load();
  } catch (e) { console.error(e); }
  finally { saving.value = false; }
}

async function remove(id) {
  if (!confirm('Excluir este armazém?')) return;
  await warehouses.destroy(id);
  await load();
}

onMounted(load);
</script>
