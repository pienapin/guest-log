<template>
  <table data-theme="light" class="table table-compact rounded-lg w-full">
    <tr class="text-center">
      <td class="font-bold">No</td>
      <th class="text-start">Waktu Kunjungan</th>
      <th class="text-start">Nama</th>
      <th>Jabatan dan Instansi</th>
      <th>No. HP</th>
      <th>No. WA</th>
      <th>Kategori</th>
      <th class="text-start">Tujuan</th>
      <th>Action</th>
    </tr>
    <tr>
      <td class="text-center"><i class="fa-solid fa-magnifying-glass"></i></td>
      <td><Datepicker placeholder="Tanggal" v-model="search.waktu_kunjungan" :enable-time-picker="false" model-type="yyyy-MM-dd" range position="left" :preset-ranges="presetRanges" /></td>
      <td><input type="text" placeholder="Nama" v-model="search.keyword" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td class="px-6">
        <input type="text" placeholder="Jabatan" v-model="search.jabatan" class="input input-bordered input-sm w-1/2 max-w-xs me-3" />
        <input type="text" placeholder="Instansi" v-model="search.instansi" class="input input-bordered input-sm w-1/2 max-w-xs" />
      </td>
      <td><input type="text" placeholder="No. HP" v-model="search.no_hp" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="No. Whatsapp" v-model="search.no_wa" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Kategori" v-model="search.kategori" class="input input-bordered input-sm w-full max-w-xs" /></td>
    </tr>
    <tbody :key="renderCount">
        <tr v-if="kunjunganList" v-for="(kunjungan, index) in kunjunganList.data" class="hover text-center">
          <div class="hidden">
            {{ pengunjung = kunjungan.pengunjung }}
            {{ kategori = kunjungan.kategori }}
          </div>
          <th>{{ index + 1 }}</th>
          <td class="text-start">{{ kunjungan.waktu_kunjungan }}</td>
          <td class="text-start">{{ pengunjung.nama }}</td>
          <td>{{ pengunjung.jabatan }} di {{ pengunjung.instansi }}</td>
          <td>{{ pengunjung.no_hp }}</td>
          <td>{{ pengunjung.no_wa }}</td>
          <td>{{ kategori.kategori }}</td>
          <td class="text-justify whitespace-normal">{{ kunjungan.tujuan }}</td>
          <td class="text-center">
            <label :for="'hapus_' + kunjungan.id" @click="stopPolling"
              class="btn mx-1 btn-error text-base-100 btn-square min-h-0 h-8 w-8 p-0 text-xs">
              <i class="fa-solid fa-trash"></i>
            </label>
            <HapusKunjungan :poll="startPolling" :kunjungan="kunjungan"></HapusKunjungan>
          </td>
        </tr>
    </tbody>
  </table>
  <div v-if="kunjunganList" class="btn-group w-full justify-center mt-5">
    <button v-if="kunjunganList.prev_page_url" class="btn" @click="currentPage -= 1">«</button>
    <button class="btn">Page {{ kunjunganList.current_page }} of {{ kunjunganList.last_page }}</button>
    <button v-if="kunjunganList.next_page_url" class="btn" @click="currentPage += 1">»</button>
  </div>
</template>

<script setup>
/* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, ref, reactive } from 'vue';
import { getKunjunganPage, searchKunjungan } from '../../services/kunjungan';
import HapusKunjungan from '../../components/HapusKunjungan.vue';
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths } from 'date-fns';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

let intervalId;
let kunjunganList;
const renderCount = ref(0);
const currentPage = ref(1);
const isSearching = ref(false)

const presetRanges = ref([
  { 
    label: 'Hari ini',
    range: [new Date(), new Date()] },
  {
    label: 'Bulan ini',
    range: [startOfMonth(new Date()), endOfMonth(new Date())]
  },
  {
    label: 'Bulan lalu',
    range: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
  },
  { label: 'Tahun ini',
    range: [startOfYear(new Date()), endOfYear(new Date())]
  },
]);

const search = reactive({
  waktu_kunjungan: null,
  keyword: null,
  instansi: null,
  jabatan: null,
  email: null,
  no_hp: null,
  no_wa: null,
  kategori: null,
});

const fetchData = async () => {
  for (const key of Object.keys(search)) {
    const element = search[key];
    if (element !== null) {
      if (element !== "") {
        isSearching.value = true;
      }
    }
  }

  if (isSearching.value) {
    kunjunganList = await searchKunjungan(search, currentPage.value)
  } else {
    kunjunganList = await getKunjunganPage(currentPage.value);
  }
  console.log(kunjunganList)
  renderCount.value += 1;
}

onMounted(() =>{
  startPolling();
});

onBeforeUnmount(() => {
  stopPolling();
});




/* ==================== functions ==================== */
const startPolling = () => {
  fetchData();
  renderCount.value += 1;
  intervalId = setInterval(() => {
    fetchData();
    renderCount.value += 1;
  }, 2000);
}


const stopPolling = () => {
  clearInterval(intervalId);
}
</script>

<style scoped>
  * {
    --dp-border-radius: .6rem;
    --dp-font-size: .875rem;
  }
</style>