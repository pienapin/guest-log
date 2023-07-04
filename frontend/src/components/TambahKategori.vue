<template>
  <Teleport to="body">
  <input type="checkbox" id="modal_tambah" class="modal-toggle" />
    <div data-theme="light" class="modal">
      <div class="modal-box relative max-w-3xl">
        <div class="row">
          <h1 class="uppercase font-bold text-2xl">Tambah Kategori</h1>
          <div class="divider mt-2"></div>
        </div>
          <form class="form-control ff-roboto">
            <label class="label label-text">Kategori</label>
            <input type="text" class="input input-bordered w-full" v-model="form.kategori"/>
            <div class="mt-6 divider"></div>
            <div class="modal-action">
              <label for="modal_tambah" @click="poll" class="btn btn-error text-base-100">Batal</label>
              <label for="modal_tambah" @click="add" class="btn btn-success border-0 ms-auto px-8 text-base-100">Submit</label>
            </div>
          </form>
      </div>
    </div>
</Teleport>  
</template>

<script setup>
import { reactive} from 'vue';
import { addKategori } from '../services/kategori';


const props = defineProps({
  poll: Function,
});

const form = reactive({
  kategori: null,
});

function add() {
  addKategori(form)
    .then((result) => {})
  props.poll()
}
</script>