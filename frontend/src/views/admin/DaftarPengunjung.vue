<template>
  <table data-theme="light" class="table table-compact rounded-lg w-full">
    <tr class="text-center">
      <th>No</th>
      <th class="text-start">Nama</th>
      <th>Instansi</th>
      <th>Jabatan</th>
      <th>Email</th>
      <th>No. HP</th>
      <th>No. WA</th>
      <th>Gambar Wajah</th>
      <th>Action</th>
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
      <td>
        <div>
          <!-- The button to open modal -->
          <label for="modal_export" class="btn btn-sm btn-outline btn-success">Export</label>

          <!-- Put this part before </body> tag -->
          <input type="checkbox" id="modal_export" class="modal-toggle" />
          <div class="modal">
            <div class="modal-box" style="--tw-translate-y: -3rem;">
              <h3 class="font-bold text-lg mb-4">Export to XLSX</h3>
              <p class="mb-2">Export berdasarkan filter : </p>
              <div class="justify-center flex flex-col">
                <input type="text" placeholder="Nama" v-model="search.keyword" class="input input-bordered input-sm w-full mt-3" />
                <input type="text" placeholder="Instansi" v-model="search.instansi" class="input input-bordered input-sm w-full mt-3" />
                <input type="text" placeholder="Jabatan" v-model="search.jabatan" class="input input-bordered input-sm w-full mt-3"/>
              </div>
              <div class="modal-action">
                <button class="btn btn-success" @click="export_xls()">Export</button>
                <label for="modal_export" class="btn btn-error">Close</label>
              </div>
            </div>
          </div>
        </div>
      </td>
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
            <td><label class="cursor-pointer hover:text-cyan-600" :for="pengunjung.wajah_pengunjung" @click="stopPolling">{{ pengunjung.wajah_pengunjung }}</label></td>
            <td class="text-center flex">
              <label :for="'edit_'+pengunjung.id" @click="stopPolling" class="btn mx-1 btn-success text-base-100 min-h-0 h-8 w-8 p-0 text-xs">
                <i class="fa-solid fa-pen"></i>
              </label>
              <EditPengunjung :poll="startPolling"  :pengunjung="pengunjung"></EditPengunjung>
    
              <label :for="'hapus_'+pengunjung.id" @click="stopPolling" class="btn mx-1 btn-error text-base-100 btn-square min-h-0 h-8 w-8 p-0 text-xs">
                <i class="fa-solid fa-trash"></i>
              </label>
              <HapusPengunjung :poll="startPolling" :pengunjung="pengunjung"></HapusPengunjung>
            </td>
            <Teleport to="body">
            <input type="checkbox" :id="pengunjung.wajah_pengunjung" class="modal-toggle" />
            <div class="modal">
              <div class="modal-box max-xl:max-w-5xl">
                <label :for="pengunjung.wajah_pengunjung" class="btn btn-sm btn-circle btn-ghost absolute right-[0.5px] top-[0.5px]" @click="startPolling">✕</label>
                <img :id="pengunjung.wajah_pengunjung+'img'" :src="hostname+'/pengunjung/'+pengunjung.wajah_pengunjung" class="mx-auto">
              </div>
              <label :for="pengunjung.wajah" class="modal-backdrop" @click="startPolling"></label>
            </div>
          </Teleport>
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
import { getPengunjungPage, searchPengunjung, exportPengunjung } from '@/services/pengunjung'
import HapusPengunjung from '../../components/HapusPengunjung.vue';
import EditPengunjung from '../../components/EditPengunjung.vue';


let intervalId;
let pengunjungList;
const renderCount = ref(0);
const currentPage = ref(1);
const perPage = ref(15);
const isSearching = ref(false)
const hostname = import.meta.env.VITE_BASE_API_URL;

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

const export_xls = async () => {
  const data = await exportPengunjung(search);
  console.log(data);
}
</script>