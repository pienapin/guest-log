<template>
  <table data-theme="light" class="table table-compact rounded-lg w-full">
    <tbody :key="renderCount">
      <tr class="text-center">
        <th>No</th>
        <th class="text-start">Nama</th>
        <th>Instansi</th>
        <th>Jabatan</th>
        <th>Email</th>
        <th>No. HP</th>
        <th>No. WA</th>
        <th>Action</th>
      </tr>
        <tr v-if="pengunjungList" v-for="(pengunjung, index) in pengunjungList.data" class="hover text-center">
          <th>{{ index+1 }}</th>
            <td class="text-start">{{ pengunjung.nama }}</td>
            <td>{{ pengunjung.instansi }}</td>
            <td>{{ pengunjung.jabatan }}</td>
            <td>{{ pengunjung.email }}</td>
            <td>{{ pengunjung.no_hp }}</td>
            <td>{{ pengunjung.no_wa }}</td>
            <td class="text-center">
              <label :for="'edit_'+pengunjung.id" class="btn mx-1 btn-success text-base-100 min-h-0 h-8 w-8 p-0 text-xs">
                <i class="fa-solid fa-pen"></i>
              </label>
              <!-- <EditPengunjung :pengunjung="pengunjung"></EditPengunjung> -->
    
              <label :for="'hapus_'+pengunjung.id" class="btn mx-1 btn-error text-base-100 btn-square min-h-0 h-8 w-8 p-0 text-xs">
                <i class="fa-solid fa-trash"></i>
              </label>
              <!-- <HapusPengunjung :pengunjung="pengunjung"></HapusPengunjung> -->
            </td>
          </tr>
    </tbody>
  </table>
  <div v-if="pengunjungList" class="btn-group mt-5">
    <button v-if="pengunjungList.prev_page_url" class="btn" @click="currentPage -= 1">«</button>
    <button class="btn">Page {{ pengunjungList.current_page }} of {{ pengunjungList.last_page }}</button>
    <button v-if="pengunjungList.next_page_url" class="btn" @click="currentPage += 1">»</button>
  </div>
</template>

<script setup>
/* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { getPengunjungPage } from '@/services/pengunjung'

let intervalId;
let pengunjungList;
const renderCount = ref(0);
const currentPage = ref(1);

const fetchData = async () => {
  pengunjungList = await getPengunjungPage(currentPage.value);
  console.log(pengunjungList)
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