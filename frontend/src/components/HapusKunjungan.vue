<template>
  <Teleport to="body">
    <input type="checkbox" :id="'hapus_'+kunjungan.id" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg">Konfirmasi</h3>
        <p class="py-4">Apakah anda yakin ingin menghapus kunjungan dengan 
          <span class="font-bold">ID {{ kunjungan.id }}</span>
        dari daftar kunjungan?</p>
        <div class="modal-action">
          <label :for="'hapus_'+kunjungan.id" @click="hapus" class="btn btn-error text-base-100">Hapus</label>
          <label :for="'hapus_'+kunjungan.id" @click="poll" class="btn btn-base-200">Batal</label>
        </div>
      </div>
    </div>
  </Teleport>
  </template>
  
<script setup>
import { delKunjungan } from '../services/kunjungan';

  
const props = defineProps({
  kunjungan: Object,
  poll: Function,
});

function hapus() {
  delKunjungan(props.kunjungan.id)
  .then((result) => {
      console.log(result);
    });
  props.poll()
}
  </script>