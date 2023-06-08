<template>
  <table data-theme="light" class="table table-compact rounded-lg w-full">
    <tr class="text-center">
      <th>No</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Email</th>
      <th>No. WA</th>
      <th>Role</th>
      <th>Action</th>
    </tr>
    <tr>
      <th class="text-center"><i class="fa-solid fa-magnifying-glass"></i></th>
      <td><input type="text" placeholder="Type here" v-model="search.nip" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Type here" v-model="search.nama" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Type here" v-model="search.email" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td><input type="text" placeholder="Type here" v-model="search.no_wa" class="input input-bordered input-sm w-full max-w-xs" /></td>
      <td colspan="2" class="text-center">
        <button class="btn btn-success h-8 min-h-8 mx-auto w-full">Tambah Pengguna</button>
      </td>
    </tr>
    <tbody :key="renderCount">
        <tr v-if="userList && userList.data.length > 0" v-for="(user, index) in userList.data" class="hover text-center">
          <th>{{ index + 1 + ((currentPage-1) * perPage)  }}</th>
            <td>{{ user.nip }}</td>
            <td>{{ user.nama }}</td>
            <td>{{ user.email }}</td>
            <td><a :href="'https://wa.me/'+user.no_wa">{{ user.no_wa }}</a></td>
            <td>
              <div v-if="user.role_id == 1" class="badge badge-primary uppercase text-white font-semibold py-3">Admin</div>
              <div v-if="user.role_id == 2" class="badge badge-secondary uppercase text-white font-semibold py-3">Kepala</div>
              <div v-if="user.role_id == 3" class="badge badge-neutral uppercase text-white font-semibold py-3">PST</div>
            </td>
            <td class="text-center">
              <label :for="'edit_'+user.id" @click="stopPolling" class="btn mx-1 btn-success text-base-100 min-h-0 h-8 w-8 p-0 text-xs">
                <i class="fa-solid fa-pen"></i>
              </label>
              <EditPengguna :poll="startPolling"  :user="user"></EditPengguna>
    
              <label :for="'hapus_'+user.id" @click="stopPolling" class="btn mx-1 btn-error text-base-100 btn-square min-h-0 h-8 w-8 p-0 text-xs">
                <i class="fa-solid fa-trash"></i>
              </label>
              <HapusPengguna :poll="startPolling" :user="user"></HapusPengguna>
            </td>
          </tr>
          <tr v-else>
            <td colspan="8" class="text-center">Data belum tersedia</td>
          </tr>
    </tbody>
  </table>
  <div v-if="userList" class="btn-group w-full justify-center mt-5">
    <button v-if="userList.prev_page_url" class="btn" @click="currentPage -= 1">«</button>
    <button class="btn">Page {{ userList.current_page }} of {{ userList.last_page }}</button>
    <button v-if="userList.next_page_url" class="btn" @click="currentPage += 1">»</button>
  </div>
</template>

<script setup>
  /* ==================== imports ==================== */
import { onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { getUserPage, searchUser } from '@/services/user'
import EditPengguna from '@/components/EditPengguna.vue';
import HapusPengguna from '@/components/HapusPengguna.vue';

let intervalId;
let userList;
const renderCount = ref(0);
const currentPage = ref(1);
const perPage = ref(15);
const isSearching = ref(false)

const search = reactive({
  nama: null,
  nip: null,
  no_wa: null,
  email: null,
});

const fetchData = async () => {
  for (const key of Object.keys(search)) {
    const element = search[key];
    console.log(key)
    console.log(element)
    console.log(element !== null)
    console.log(element !== "")
    console.log(isSearching)
    if (element !== null) {
      if (element !== "") {
        isSearching.value = true;
      }
    }
  }

  if (isSearching.value) {
    userList = await searchUser(search, currentPage.value);
  } else {
    userList = await getUserPage(currentPage.value);
  }
  console.log(userList)
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