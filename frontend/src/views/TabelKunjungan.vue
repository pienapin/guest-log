<template>
  <!-- Navbar -->
  <div>
      <div class="navbar py-3 px-5">
        <div class="navbar-start max-md:flex max-md:flex-col">
          <img class="w-14 mx-5" src="@/assets/img/bps-logo.png">
          <div class="grid grid-flow-row">
            <a class="uppercase font-medium text-2xl ff-oswald text-center">BUTEP BPS Provinsi Riau</a>
            <h3 class="uppercase font-medium text-sm ff-oswald text-center">Buku Tamu Elektronik Pengenal Wajah</h3>
          </div>
        </div>
        <div class="navbar-center max-md:flex max-md:flex-col">
          
        </div>
        <div class="navbar-end max-md:flex max-md:flex-col">
          <div class="dropdown dropdown-end lg:hidden">
            <label tabindex="0" class="btn btn-ghost">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
              <li><router-link to="/">Beranda</router-link></li>
              <li><router-link to="/isi">Isi Buku Tamu</router-link></li>
              <li><router-link to="/kunjungan">Daftar Kunjungan</router-link></li>
            </ul>
          </div>
          <ul class="menu menu-horizontal px-1 hidden lg:flex">
            <li><router-link to="/" class="text-lg">Beranda</router-link></li>
            <li><router-link to="/isi" class="text-lg">Isi Buku Tamu</router-link></li>
            <li><router-link to="/kunjungan" class="text-lg">Daftar Kunjungan</router-link></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="w-full flex justify-center my-8">
      <router-link to="/isi" class="uppercase btn btn-success text-base-100 text-lg">Isi Buku Tamu</router-link>
    </div>

    <div class="mx-10">
      <table data-theme="light" class="table border table-zebra rounded-lg w-full">
      <tr class="text-center border">
        <td class="font-bold">No</td>
        <th>Waktu Kunjungan</th>
        <th>Nama</th>
        <th>Jabatan dan Instansi</th>
        <th>Kategori</th>
        <th>Tujuan</th>
      </tr>
      <tr>
        <td class="text-center"><i class="fa-solid fa-magnifying-glass"></i></td>
        <td><Datepicker placeholder="Tanggal" v-model="search.waktu_kunjungan" :enable-time-picker="false" model-type="yyyy-MM-dd" range position="left" :preset-ranges="presetRanges" /></td>
        <td><input type="text" placeholder="Nama" v-model="search.keyword" class="input input-bordered input-sm w-full max-w-xs" /></td>
        <td class="px-5 flex mt-1">
          <input type="text" placeholder="Jabatan" v-model="search.jabatan" class="input input-bordered input-sm w-1/2 max-w-xs me-3" />
          <input type="text" placeholder="Instansi" v-model="search.instansi" class="input input-bordered input-sm w-1/2 max-w-xs" />
        </td>
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
            <td>{{ pengunjung.nama }}</td>
            <td>{{ pengunjung.jabatan }} di {{ pengunjung.instansi }}</td>
            <td>{{ kategori.kategori }}</td>
            <td class="text-justify whitespace-normal">{{ kunjungan.tujuan }}</td>
          </tr>
          <tr v-else>
            <td colspan="9" class="text-center">Data belum tersedia</td>
          </tr>
      </tbody>
    </table>

    </div>
  <div v-if="kunjunganList" class="w-full justify-between mt-5 flex">
    <div></div>
    <div class="join">
      <button v-if="kunjunganList.prev_page_url" class="btn" @click="currentPage -= 1">«</button>
      <button class="btn">Page {{ kunjunganList.current_page }} of {{ kunjunganList.last_page }}</button>
      <button v-if="kunjunganList.next_page_url" class="btn" @click="currentPage += 1">»</button>
    </div>
    <div></div>
  </div>

  <!-- Footer -->
  <div class="h-[32px]">
    <footer class="footer footer-center p-3 absolute bottom-0">
      <div>
        <p>Copyright © 2023 - All right reserved by Magang MBKM Mandiri by BPS Riau - Universitas Riau 2023</p>
      </div>
    </footer>
  </div>

</template>

<script setup>
/* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, ref, reactive } from 'vue';
import { getKunjunganPage, searchKunjungan } from '@/services/kunjungan';
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
    kunjunganList = await getKunjunganPage(currentPage.value, perPage.value);
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