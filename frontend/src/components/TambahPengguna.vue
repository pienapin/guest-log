<template>
  <Teleport to="body">
  <input type="checkbox" id="tambah" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box relative max-w-3xl">
        <div class="row">
          <h1 class="uppercase font-bold text-2xl">Tambah Pengguna</h1>
          <div class="divider mt-2"></div>
        </div>
          <form class="form-control ff-roboto">
            <label class="label label-text">NIP Pengguna</label>
            <input type="text" class="input input-bordered w-full" v-model="form.nip"/>
            <div class="grid grid-rows-2 gap-4">
              <div class="grid grid-cols-2 gap-10">
                <div>
                  <label class="label label-text">Nama Lengkap Pengguna</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.nama"/>
                </div>
                <div>
                  <label class="label label-text">Email Pengguna</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.email"/>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-10">
                <div>
                  <label class="label label-text">Role ID (1: Admin; 2: Kepala; 3: PST)</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.role_id"/>
                </div>
                <div>
                  <label class="label label-text">Nomor WA</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.no_wa"/>
                </div>
              </div>
            </div>
            <div class="mt-6 divider"></div>
            <div class="modal-action">
              <label for="tambah" @click="poll" class="btn btn-error text-base-100">Batal</label>
              <label for="tambah" @click="add" class="btn btn-success border-0 ms-auto px-8 text-base-100">Submit</label>
            </div>
          </form>
      </div>
    </div>
</Teleport>  
</template>

<script setup>
import { onUpdated, reactive, ref } from 'vue';
import { addUser } from '../services/user';


const props = defineProps({
  poll: Function,
});

const form = reactive({
  nip: null,
  nama: null,
  email: null,
  no_wa: null,
  role_id: null,
});

function add() {
  addUser(form)
    .then((result) => {})
  props.poll()
}
</script>