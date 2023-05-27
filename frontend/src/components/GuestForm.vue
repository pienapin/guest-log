<template>
  <input type="checkbox" id="guest-form" class="modal-toggle" />
  <div class="modal fix-modal">
    <div class="modal-box relative max-w-3xl">
      <div class="row">
        <h1 class="uppercase font-bold text-2xl">Informasi Kunjungan</h1>
        <div class="divider"></div>
        <label class="btn btn-xs btn-error btn-circle absolute right-2 top-2" @click="$emit('close')"></label>
      </div>
      <form @submit.prevent="submit" id="guest-form" class="form-control ff-roboto">
        <div>
          <div class="grid sm:grid-cols-2 max-sm:grid-rows-2 sm:gap-10 mb-3">
            <div>
              <label class="label label-text">Nama Lengkap Pengunjung</label>
              <input type="text" class="input input-bordered w-full" v-model="form.nama" required />
            </div>
            <div>
              <label class="label label-text">Email Pengunjung</label>
              <input type="email" class="input input-bordered w-full" v-model="form.email" required />
            </div>
          </div>
          <div class="grid sm:grid-cols-2 sm:gap-10 mb-3">
            <div>
              <label class="label label-text">Asal Instansi Pengunjung</label>
              <input type="text" class="input input-bordered w-full" v-model="form.instansi" required />
            </div>
            <div class="sm:hidden">
              <label class="label label-text">Jabatan Pengunjung</label>
              <input type="text" class="input input-bordered w-full" v-model="form.jabatan" required />
            </div>
            <div class="max-sm:hidden">
              <label class="label label-text">Nomor Telepon</label>
              <input type="tel" pattern="[0-9]{10}|[0-9]{11}|[0-9]{12}|[0-9]{13}" class="input input-bordered w-full"
                v-model="form.no_hp" />
            </div>
          </div>
          <div class="grid sm:grid-cols-2 sm:gap-10 mb-3">
            <div class="max-sm:hidden">
              <label class="label label-text">Jabatan Pengunjung</label>
              <input type="text" class="input input-bordered w-full" v-model="form.jabatan" required />
            </div>
            <div class="sm:hidden">
              <label class="label label-text">Nomor Telepon</label>
              <input type="tel" pattern="[0-9]{10}|[0-9]{11}|[0-9]{12}|[0-9]{13}" class="input input-bordered w-full"
                v-model="form.no_hp" />
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
              <input type="tel" pattern="[0-9]{10}|[0-9]{11}|[0-9]{12}|[0-9]{13}" placeholder="081234567890"
                class="input input-bordered w-full" v-model="form.no_wa" :disabled="telepon_wa" />
            </div>
          </div>
          <div class="mb-3">
            <label class="label label-text">Kategori Kunjungan</label>
            <select class="select select-bordered w-full font-normal" v-model="form.kategori_id" required>
              <option disabled selected value="0">Pilih Kategori Kunjungan</option>
              <!-- <option v-for="kat in kategori" :value="kat.id">{{ kat.kategori }}</option> -->
            </select>
          </div>
          <div>
            <label class="label label-text">Tujuan Kunjungan</label>
            <textarea rows="3" class="textarea textarea-bordered textarea-md leading-4 w-full" v-model="form.tujuan"
              required />
          </div>
        </div>
        <div class="mt-6 divider"></div>
        <div class="grid grid-cols-2">
          <h3 class="self-center sm:text-lg max-sm:text-md">BUKAN ANDA? <span
              class="text-info font-bold underline cursor-pointer" @click="$emit('close')">DETEKSI ULANG</span></h3>
          <button type="submit" class="btn btn-success border-0 ms-auto px-8 text-base-100"
            :disabled="form.kategori_id == 0 || !isValid">Submit</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onBeforeUpdate, onUpdated } from 'vue';

const props = defineProps({
  pengunjung: Object,
  descriptorDetected: String,
});

const emit = defineEmits(['close']);

const form = reactive({
  isExists: false,
  pengunjung_id: null,
  nama: null,
  instansi: null,
  jabatan: null,
  email: null,
  no_hp: null,
  no_wa: null,
  descriptors: null,
  kategori_id: 0,
  tujuan: null,
});

const isPengunjungExists = ref(false);
const telepon_wa = ref(false);
const isValid = ref(false);

if (props.pengunjung) {
  form.isExists = true;
  form.pengunjung_id = props.pengunjung.id;
  form.nama = props.pengunjung.nama;
  form.instansi = props.pengunjung.instansi;
  form.jabatan = props.pengunjung.jabatan;
  form.email = props.pengunjung.email;
  form.no_hp = props.pengunjung.no_hp;
  form.no_wa = props.pengunjung.no_wa;
  form.descriptors = props.pengunjung.descriptors;

  isPengunjungExists.value = true;
} else {
  form.isExists = false;
  form.descriptors = props.descriptorDetected;
}

onBeforeUpdate(() => {
  isValid.value = true;

  const guest_form = document.getElementsByClassName('input');

  Array.from(guest_form).forEach(input => {
    if (!input.checkValidity()) {
      isValid.value = false;
    }
  });
})

onUpdated(() => {
  if (telepon_wa.value == true) {
    form.no_wa = form.no_hp;
  }
})




// add function press escape to close form
const escapeToClose = ({ code }) => {
  if (code === "Escape") {
    emit('close');
  }
}
document.addEventListener('keyup', escapeToClose, true);

// for submitting form
const submit = () => {
  router.post('/submit', form);
  emit('close');
}
</script>