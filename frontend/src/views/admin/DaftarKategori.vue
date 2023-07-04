<template>
  <table data-theme="light" class="table table-compact rounded-lg w-full">
    <tr class="text-center">
      <th>No</th>
      <th class="text-start">Kategori</th>
      <th><label for="modal_tambaah" class="btn btn-sm btn-outline btn-success">Tambah Kategori</label></th>
    </tr>
    <tbody :key="renderCount">
        <tr v-if="kategoriList && kategoriList.data.length > 0" v-for="(kategori, index) in kategoriList.data" class="hover text-center">
          <th>{{ index + 1 }}</th>
            <td class="text-start">{{ kategori.kategori }}</td>
            <!-- <td class="text-center flex">
              <label :for="'edit_'+pengunjung.id" @click="stopPolling" class="btn mx-1 btn-success text-base-100 min-h-0 h-8 w-8 p-0 text-xs">
                <i class="fa-solid fa-pen"></i>
              </label>
              <EditPengunjung :poll="startPolling"  :pengunjung="pengunjung"></EditPengunjung>
    
              <label :for="'hapus_'+pengunjung.id" @click="stopPolling" class="btn mx-1 btn-error text-base-100 btn-square min-h-0 h-8 w-8 p-0 text-xs">
                <i class="fa-solid fa-trash"></i>
              </label>
              <HapusPengunjung :poll="startPolling" :pengunjung="pengunjung"></HapusPengunjung>
            </td> -->
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
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { getKategoriList } from '@/services/kategori';


let intervalId;
let kategoriList;
const renderCount = ref(0);
const hostname = import.meta.env.VITE_BASE_API_URL;

const fetchData = async () => {
  kategoriList = await getKategoriList();
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