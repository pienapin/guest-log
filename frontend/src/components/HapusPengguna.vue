<template>
  <Teleport to="body">
    <input type="checkbox" :id="'hapus_'+user.id" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg">Konfirmasi</h3>
        <p class="py-4">Apakah anda yakin ingin menghapus  
          <span class="font-bold">{{ user.nama }}</span>
        dari daftar pengguna?</p>
        <div class="modal-action">
          <label :for="'hapus_'+user.id" @click="hapus" class="btn btn-error text-base-100">Hapus</label>
          <label :for="'hapus_'+user.id" @click="poll" class="btn btn-base-200">Batal</label>
        </div>
      </div>
    </div>
  </Teleport>
  </template>
  
<script setup>
import { delUser } from '../services/user';

  
const props = defineProps({
  user: Object,
  poll: Function,
});

function hapus() {
  delUser(props.user.id)
  .then((result) => {});
  props.poll()
}
  </script>