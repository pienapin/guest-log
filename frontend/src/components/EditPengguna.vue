<template>
  <Teleport to="body">
  <input type="checkbox" :id="'edit_'+user.id" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box relative max-w-3xl">
        <div class="row">
          <h1 class="uppercase font-bold text-2xl">Ubah Data Pengguna</h1>
          <div class="divider mt-2"></div>
        </div>
          <form class="form-control ff-roboto">
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
              <label :for="'edit_'+user.id" @click="poll" class="btn btn-error text-base-100">Batal</label>
              <label :for="'edit_'+user.id" @click="edit" class="btn btn-success border-0 ms-auto px-8 text-base-100">Submit</label>
            </div>
          </form>
      </div>
    </div>
</Teleport>  
</template>

<script setup>
import { onUpdated, reactive, ref } from 'vue';
import { editUser } from '../services/user';


const props = defineProps({
  user: Object,
  poll: Function,
});

const form = reactive({
  id: props.user.id,
  nip: props.user.nip,
  nama: props.user.nama,
  email: props.user.email,
  no_wa: props.user.no_wa,
  role_id: props.user.role_id,
});

function edit() {
  editUser(form)
    .then((result) => {})
  props.poll()
}
</script>