<template>
  <table data-theme="light" class="table table-compact rounded-lg w-full">
    <tr class="text-center">
      <td class="font-bold">No</td>
      <th>Waktu Kunjungan</th>
      <th>Nama</th>
      <th>Jabatan dan Instansi</th>
      <th>No. HP</th>
      <th>No. WA</th>
      <th>Kategori</th>
      <th>Tujuan</th>
    </tr>
    <tr>
      <td class="text-center"><i class="fa-solid fa-magnifying-glass"></i></td>
      <td><Datepicker placeholder="Tanggal" v-model="search.waktu_kunjungan" :enable-time-picker="false" model-type="yyyy-MM-dd" range position="left" :preset-ranges="presetRanges" /></td>
      <td><input type="text" placeholder="Nama" v-model="search.keyword" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td class="px-6 flex">
        <input type="text" placeholder="Jabatan" v-model="search.jabatan" class="input input-bordered input-sm w-1/2 max-w-xs me-3" />
        <input type="text" placeholder="Instansi" v-model="search.instansi" class="input input-bordered input-sm w-1/2 max-w-xs" />
      </td>
      <td><input type="text" placeholder="No. HP" v-model="search.no_hp" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="No. Whatsapp" v-model="search.no_wa" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Kategori" v-model="search.kategori" class="input input-bordered input-sm w-full max-w-xs" /></td>
    </tr>
    <tbody :key="renderCount">
        <tr v-if="kunjunganList && kunjunganList.data.length > 0" v-for="(kunjungan, index) in kunjunganList.data" class="hover text-center">
          <div class="hidden">
            {{ pengunjung = kunjungan.pengunjung }}
            {{ kategori = kunjungan.kategori }}
          </div>
          <th>{{ index + 1 + ((currentPage-1) * perPage) }}</th>
          <td class="text-center">{{ kunjungan.waktu_kunjungan }}</td>
          <td class="text-start">{{ pengunjung.nama }}</td>
          <td>{{ pengunjung.jabatan }} di {{ pengunjung.instansi }}</td>
          <td>{{ pengunjung.no_hp }}</td>
          <td>{{ pengunjung.no_wa }}</td>
          <td>{{ kategori.kategori }}</td>
          <td class="text-justify whitespace-normal">{{ kunjungan.tujuan }}</td>
        </tr>
        <tr v-else>
          <td colspan="9" class="text-center">Data belum tersedia</td>
        </tr>
    </tbody>
  </table>
  <div v-if="kunjunganList" class="w-full justify-between mt-5 flex">
    <div></div>
    <div class="join">
      <button v-if="kunjunganList.prev_page_url" class="btn" @click="currentPage -= 1">«</button>
      <button class="btn">Page {{ kunjunganList.current_page }} of {{ kunjunganList.last_page }}</button>
      <button v-if="kunjunganList.next_page_url" class="btn" @click="currentPage += 1">»</button>
    </div>
    <div>
    </div>
  </div>
</template>

<script setup>
/* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, ref, reactive } from 'vue';
import { getKunjunganPage, searchKunjungan } from '../../services/kunjungan';
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths } from 'date-fns';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

let intervalId;
let kunjunganList;
const renderCount = ref(0);
const currentPage = ref(1);
const perPage = ref(15);
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
  {
    label: '3 Bulan Terakhir',
    range: [startOfMonth(subMonths(new Date(), 2)), endOfMonth(new Date())]
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