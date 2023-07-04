<template>
  <div class="overflow-x-auto">

    <table data-theme="light" class="table table-compact rounded-lg w-full">
      <tr class="text-center">
        <td class="font-bold">No</td>
        <th>Waktu Kunjungan</th>
        <th>Petugas</th>
        <th>Nama</th>
        <th>Jabatan dan Instansi</th>
        <th>Tujuan</th>
        <th>Data yang diminta</th>
        <th>Keterangan Pelayanan</th>
        <th>Dokumentasi</th>
        <th>Status</th>
      </tr>
      <tr>
        <td class="text-center"><i class="fa-solid fa-magnifying-glass"></i></td>
        <td><Datepicker placeholder="Tanggal" v-model="search.waktu_kunjungan" :enable-time-picker="false" model-type="yyyy-MM-dd" range position="left" :preset-ranges="presetRanges" /></td>
        <td><input type="text" placeholder="Petugas" v-model="search.petugas" class="input input-bordered input-sm w-full max-w-xs" /></td>
        <td><input type="text" placeholder="Nama" v-model="search.pengunjung" class="input input-bordered input-sm w-full max-w-xs" /></td>
        <td class="px-6 flex">
          <input type="text" placeholder="Jabatan" v-model="search.jabatan" class="input input-bordered input-sm w-1/2 max-w-xs me-3" />
          <input type="text" placeholder="Instansi" v-model="search.instansi" class="input input-bordered input-sm w-1/2 max-w-xs" />
        </td>
        <td><input type="text" placeholder="Tujuan" v-model="search.tujuan" class="input input-bordered input-sm w-full max-w-xs" /></td>
        <td><input type="text" placeholder="Data yang diminta" v-model="search.data_diminta" class="input input-bordered input-sm w-full max-w-xs" /></td>
        <td></td>
        <td></td>
        <td><input type="text" placeholder="Status Layanan" v-model="search.status_layanan" class="input input-bordered input-sm w-full max-w-xs" /></td>
      </tr>
      <tbody :key="renderCount">
          <tr v-if="pelayananList && pelayananList.data.length > 0" v-for="(pelayanan, index) in pelayananList.data" class="hover text-center">
            <div class="hidden">
              {{ kunjungan = pelayanan.kunjungan }}
              {{ user = pelayanan.user }}
              {{ pengunjung = pelayanan.kunjungan.pengunjung }}
            </div>
            <th>{{ index + 1 + ((currentPage-1) * perPage) }}</th>
            <td>{{ kunjungan.waktu_kunjungan.split(' ')[0] }}<br/>{{ kunjungan.waktu_kunjungan.split(' ')[1] }}</td>
            <td v-if="user">{{ user.nama }}</td>
            <td v-else></td>
            <td>{{ pengunjung.nama }}</td>
            <td class="whitespace-normal">{{ pengunjung.jabatan }}<br/>{{ pengunjung.instansi }}</td>
            <td class="whitespace-normal">{{ kunjungan.tujuan }}</td>
            <td>{{ pelayanan.data_diminta }}</td>
            <td class="whitespace-normal text-justify">{{ pelayanan.keterangan_pelayanan }}</td>
            <td><label class="cursor-pointer hover:text-cyan-600" :for="pelayanan.dokumentasi" @click="getImage(pelayanan.dokumentasi)">{{ pelayanan.dokumentasi }}</label></td>
            <td class="text-center" v-if="pelayanan.status_layanan != 'Belum didokumentasi'">{{ pelayanan.status_layanan }}</td>
            <td class="text-center font-bold text-red-500" v-else>Belum<br/>didokumentasi</td>
            <Teleport to="body">
            <input type="checkbox" :id="pelayanan.dokumentasi" class="modal-toggle" />
            <div class="modal">
              <div class="modal-box max-xl:max-w-5xl">
                <label :for="pelayanan.dokumentasi" class="btn btn-sm btn-circle btn-ghost absolute right-[0.5px] top-[0.5px]" @click="poll">✕</label>
                <img :id="pelayanan.dokumentasi+'img'" :src="hostname+'/pelayanan/'+pelayanan.dokumentasi" class="mx-auto">
              </div>
              <label :for="pelayanan.dokumentasi" class="modal-backdrop" @click="startPolling"></label>
            </div>
          </Teleport>
          </tr>
          <tr v-else>
            <td colspan="11" class="text-center"> Data Belum Tersedia</td>
          </tr>
      </tbody>
    </table>
  </div>
  <div v-if="pelayananList" class="join w-full justify-center mt-5">
    <button v-if="pelayananList.prev_page_url" class="btn" @click="currentPage -= 1">«</button>
    <button class="btn">Page {{ pelayananList.current_page }} of {{ pelayananList.last_page }}</button>
    <button v-if="pelayananList.next_page_url" class="btn" @click="currentPage += 1">»</button>
  </div>
</template>

<script setup>
/* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, ref, reactive } from 'vue';
import { getPelayananPage, getDokumentasiImg, searchPelayanan } from '../../services/pelayanan';
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths } from 'date-fns';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

let intervalId;
let pelayananList;
const renderCount = ref(0);
const currentPage = ref(1);
const perPage = ref(15);
const isSearching = ref(false);
const imageUrl = ref(null);

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
  petugas: null,
  instansi: null,
  jabatan: null,
  tujuan: null,
  data_diminta: null,
  status_layanan: null,
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
    pelayananList = await searchPelayanan(search, currentPage.value)
  } else {
    pelayananList = await getPelayananPage(currentPage.value);
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
  imageUrl.value = null;
  renderCount.value += 1;
  intervalId = setInterval(() => {
    fetchData();
    renderCount.value += 1;
  }, 2000);
}


const stopPolling = () => {
  clearInterval(intervalId);
}

const getImage = async (file) => {
  imageUrl.value = await getDokumentasiImg(file);
  stopPolling()
}
</script>

<style scoped>
  * {
    --dp-border-radius: .6rem;
    --dp-font-size: .875rem;
  }
</style>