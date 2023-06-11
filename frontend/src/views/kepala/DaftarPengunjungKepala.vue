<template>
  <table data-theme="light" class="table table-compact rounded-lg w-full">
    <tr class="text-center">
      <th>No</th>
      <th>Nama</th>
      <th>Instansi</th>
      <th>Jabatan</th>
      <th>Email</th>
      <th>No. HP</th>
      <th>No. WA</th>
    </tr>
    <tr>
      <th class="text-center"><i class="fa-solid fa-magnifying-glass"></i></th>
      <td><input type="text" placeholder="Type here" v-model="search.keyword" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Type here" v-model="search.instansi" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Type here" v-model="search.jabatan" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Type here" v-model="search.email" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Type here" v-model="search.no_hp" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Type here" v-model="search.no_wa" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td></td>
    </tr>
    <tbody :key="renderCount">
        <tr v-if="pengunjungList && pengunjungList.data.length > 0" v-for="(pengunjung, index) in pengunjungList.data" class="hover text-center">
          <th>{{ index + 1 + ((currentPage-1) * perPage)  }}</th>
            <td class="text-start">{{ pengunjung.nama }}</td>
            <td>{{ pengunjung.instansi }}</td>
            <td>{{ pengunjung.jabatan }}</td>
            <td>{{ pengunjung.email }}</td>
            <td>{{ pengunjung.no_hp }}</td>
            <td><a :href="'https://wa.me/'+pengunjung.no_wa">{{ pengunjung.no_wa }}</a></td>
          </tr>
          <tr v-else>
            <td colspan="8" class="text-center">Data belum tersedia</td>
          </tr>
    </tbody>
  </table>
  <div v-if="pengunjungList" class="join w-full justify-center  mt-5">
    <button v-if="pengunjungList.prev_page_url" class="btn" @click="currentPage -= 1">«</button>
    <button class="btn">Page {{ pengunjungList.current_page }} of {{ pengunjungList.last_page }}</button>
    <button v-if="pengunjungList.next_page_url" class="btn" @click="currentPage += 1">»</button>
  </div>
</template>

<script setup>
/* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { getPengunjungPage, searchPengunjung } from '@/services/pengunjung'


let intervalId;
let pengunjungList;
const renderCount = ref(0);
const currentPage = ref(1);
const perPage = ref(15);
const isSearching = ref(false)

const search = reactive({
  keyword: null,
  instansi: null,
  jabatan: null,
  email: null,
  no_hp: null,
  no_wa: null,
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
    pengunjungList = await searchPengunjung(search, currentPage.value);
  } else {
    pengunjungList = await getPengunjungPage(currentPage.value);
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