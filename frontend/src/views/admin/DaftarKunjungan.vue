<template>
  <table data-theme="light" class="table table-compact rounded-lg w-full">
    <tbody :key="renderCount">
      <tr class="text-center">
        <th>No</th>
        <th class="text-start">Nama</th>
        <th>Jabatan dan Instansi</th>
        <th>No. HP</th>
        <th>No. WA</th>
        <th>Kategori</th>
        <th class="text-start">Tujuan</th>
        <th>Action</th>
      </tr>
        <tr v-if="kunjunganList" v-for="(kunjungan, index) in kunjunganList.data" class="hover text-center">
          <div class="hidden">
            {{ pengunjung = kunjungan.pengunjung }}
            {{ kategori = kunjungan.kategori }}
          </div>
          <th>{{ index + 1 }}</th>
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
  <div v-if="kunjunganList" class="btn-group mt-5">
    <button v-if="kunjunganList.prev_page_url" class="btn" @click="currentPage -= 1">«</button>
    <button class="btn">Page {{ kunjunganList.current_page }} of {{ kunjunganList.last_page }}</button>
    <button v-if="kunjunganList.next_page_url" class="btn" @click="currentPage += 1">»</button>
  </div>
</template>

<script setup>
/* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { getKunjunganPage } from '../../services/kunjungan';
import HapusKunjungan from '../../components/HapusKunjungan.vue';

let intervalId;
let kunjunganList;
const renderCount = ref(0);
const currentPage = ref(1);

const fetchData = async () => {
  kunjunganList = await getKunjunganPage(currentPage.value);
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