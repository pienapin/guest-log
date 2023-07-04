<template>
  <Teleport to="body">
  <input type="checkbox" :id="'edit_'+pelayanan.id" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box relative max-w-3xl">
        <div class="row">
          <h1 class="uppercase font-bold text-2xl">Data Pelayanan</h1>
          <div class="divider mt-2"></div>
        </div>
          <form class="form-control ff-roboto" enctype="multipart/form-data">
            <div class="grid grid-rows-3 gap-4">
              <div class="grid gap-10">
                <div>
                  <label class="label label-text">Data yang diminta</label>
                  <input type="text" class="input input-bordered w-full" v-model="form.data_diminta"/>
                </div>
              </div>
              <div class="grid gap-10">
                <div>
                  <label class="label label-text">Dokumentasi (.jpg, .jpeg, .png)</label>
                  <input type="file" class="w-full" ref="dok" v-on:change="onFileChange"/>
                </div>
              </div>
              <div class="grid gap-10">
                <div>
                  <label class="label label-text">Status Layanan</label>
                  <select class="select select-bordered w-full font-normal" v-model="form.status_layanan" required>
                    <option disabled selected value="0">Pilih Status Layanan</option>
                    <option value="Terlayani">Terlayani</option>
                    <option value="Terlayani Sebagian">Terlayani Sebagian</option>
                    <option value="Tidak Terlayani">Tidak Terlayani</option>
                  </select>
                </div>
              </div>
              <div class="grid gap-10">
                <div>
                  <label class="label label-text">Keterangan Pelayanan</label>
                  <textarea rows="3" class="textarea textarea-bordered textarea-md leading-4 w-full" v-model="form.keterangan_pelayanan"></textarea>
                </div>
              </div>
            </div>
            <div class="mt-6 divider"></div>
            <div class="modal-action">
              <label :for="'edit_'+pelayanan.id" @click="poll" class="btn btn-error text-base-100">Batal</label>
              <label :for="'edit_'+pelayanan.id" @click="edit" class="btn btn-success border-0 ms-auto px-8 text-base-100">Submit</label>
            </div>
          </form>
      </div>
    </div>
</Teleport>  
</template>

<script setup>
import { reactive, ref } from 'vue';
import useAuthStore from '../stores/auth';
import { editPelayanan } from '../services/pelayanan';

const dok = ref(null)

const props = defineProps({
  pelayanan: Object,
  poll: Function,
});

const form = reactive({
  id: props.pelayanan.id,
  petugas_id: useAuthStore().user.id,
  data_diminta: props.pelayanan.data_diminta,
  dokumentasi: null,
  status_layanan: props.pelayanan.status,
  keterangan_pelayanan: props.pelayanan.keterangan_pelayanan,
});

function edit() {
  editPelayanan(form)
    .then((result) => {})
  props.poll()
}

function onFileChange() {
  form.dokumentasi = dok.value.files[0]
}
</script>