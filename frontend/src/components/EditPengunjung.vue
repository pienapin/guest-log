<template>
  <Teleport to="body">
  <input type="checkbox" :id="'edit_'+pengunjung.id" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box relative max-w-3xl">
        <div class="row">
          <h1 class="uppercase font-bold text-2xl">Ubah Data Pengunjung</h1>
          <div class="divider mt-2"></div>
        </div>
          <form class="form-control ff-roboto">
            <div class="grid grid-rows-3 gap-4">
              <div class="grid grid-cols-2 gap-10">
                <div>
                  <label class="label label-text">Nama Lengkap Pengunjung</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.nama"/>
                </div>
                <div>
                  <label class="label label-text">Email Pengunjung</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.email"/>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-10">
                <div>
                  <label class="label label-text">Asal Instansi Pengunjung</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.instansi"/>
                </div>
                <div>
                  <label class="label label-text">Nomor Telepon</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.no_hp"/>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-10">
                <div>
                  <label class="label label-text">Jabatan Pengunjung</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.jabatan"/>
                </div>
                <div>
                  <div class="grid grid-cols-2">
                    <label class="label label-text">Nomor Whatsapp</label>
                    <label class="switch self-center justify-content-end">
                      <input type="checkbox" v-model="telepon_wa">
                      <span class="slider"></span>
                      <span class="labels" data-on="sama dengan nomor telepon" data-off="sama dengan nomor telepon"></span>
                    </label>
                  </div>
                  <input type="text" class="input input-bordered w-full" v-model="form.no_wa" :disabled="telepon_wa"/>
                </div>
              </div>
            </div>
            <div class="mt-6 divider"></div>
            <div class="modal-action">
              <label :for="'edit_'+pengunjung.id" @click="poll" class="btn btn-error text-base-100">Batal</label>
              <label :for="'edit_'+pengunjung.id" @click="edit" class="btn btn-success border-0 ms-auto px-8 text-base-100">Submit</label>
            </div>
          </form>
      </div>
    </div>
</Teleport>  
</template>

<script setup>
import { onUpdated, reactive, ref } from 'vue';
import { addPengunjung } from '../services/pengunjung';

const telepon_wa = ref(false);

const props = defineProps({
  pengunjung: Object,
  poll: Function,
});

const form = reactive({
  pengunjung_id: props.pengunjung.id,
  nama: props.pengunjung.nama,
  instansi: props.pengunjung.instansi,
  jabatan: props.pengunjung.jabatan,
  email: props.pengunjung.email,
  no_hp: props.pengunjung.no_hp,
  no_wa: props.pengunjung.no_wa,
  descriptors: props.pengunjung.descriptors
});

function edit() {
  addPengunjung(form)
    .then((result) => {
      console.log(result);
    })
  props.poll()
}

onUpdated(() => {
  if (telepon_wa.value) {
    form.no_wa = form.no_hp;
  }
});
</script>