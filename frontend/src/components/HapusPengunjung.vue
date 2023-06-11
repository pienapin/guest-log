<template>
  <Teleport to="body">
    <input type="checkbox" :id="'hapus_'+pengunjung.id" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg">Konfirmasi</h3>
        <p class="py-4">Apakah anda yakin ingin menghapus  
          <span class="font-bold">{{ pengunjung.nama }}</span>
        dari daftar pengunjung?</p>
        <div class="modal-action">
          <label :for="'hapus_'+pengunjung.id" @click="hapus" class="btn btn-error text-base-100">Hapus</label>
          <label :for="'hapus_'+pengunjung.id" @click="poll" class="btn btn-base-200">Batal</label>
        </div>
      </div>
    </div>
  </Teleport>
  </template>
  
<script setup>
import { delPengunjung } from '../services/pengunjung';

  
const props = defineProps({
  pengunjung: Object,
  poll: Function,
});

function hapus() {
  delPengunjung(props.pengunjung.id)
  .then((result) => {});
  props.poll()
}
  </script>