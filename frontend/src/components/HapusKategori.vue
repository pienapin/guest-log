<template>
  <Teleport to="body">
    <input type="checkbox" :id="'hapus_'+kategori.id" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg">Konfirmasi</h3>
        <p class="py-4">Apakah anda yakin ingin menghapus  
          <span class="font-bold">{{ kategori.kategori }}</span>
        dari daftar kategori?</p>
        <div class="modal-action">
          <label :for="'hapus_'+kategori.id" @click="hapus" class="btn btn-error text-base-100">Hapus</label>
          <label :for="'hapus_'+kategori.id" @click="poll" class="btn btn-base-200">Batal</label>
        </div>
      </div>
    </div>
  </Teleport>
  </template>
  
<script setup>
import { delKategori } from '../services/kategori';

  
const props = defineProps({
  kategori: Object,
  poll: Function,
});

function hapus() {
  delKategori(props.kategori)
  .then((result) => {});
  props.poll()
}
  </script>