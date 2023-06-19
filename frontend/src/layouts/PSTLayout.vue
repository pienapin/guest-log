<template>
  <div class="w-screen h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <div class="card w-[240px] mx-3 my-3 bg-gray-50 drop-shadow-md hidden lg:flex">
      <!-- Sidebar Header -->
      <div class="flex my-8 me-3 justify-center">
        <img class="w-10 mx-2" src="@/assets/img/bps-logo.png">
        <a class="uppercase font-medium text-2xl ff-oswald">Guest Log</a>
      </div>
      <h3 class="text-center font-medium uppercase tracking-[.25em] text-gray-500 mb-3 px-3">Pelayanan Statistik Terpadu</h3>
      <!-- Menu -->
      <ul class="menu w-56 p-2">
        <li>
          <router-link to="/pst" class="w-full text-center py-4 focus:menu-items" exact-active-class="bg-gray-700 text-slate-100">
            <i class="fa-solid fa-house fa-lg"></i>
            Dashboard
          </router-link>
        </li>
        <li>
          <router-link to="/pst/daftar-pengunjung" class="w-full text-center py-4 focus:menu-items" exact-active-class="bg-gray-700 text-slate-100">
            <i class="fa-solid fa-address-book fa-lg"></i>
              Daftar Pengunjung
          </router-link>
        </li>
        <li class="hover:bg-gray-200 rounded-md">
          <router-link to="/pst/daftar-kunjungan" class="w-full text-center py-4 focus:menu-items" exact-active-class="bg-gray-700 text-slate-100">
            <i class="fa-solid fa-building-circle-check fa-lg"></i>
              Daftar Kunjungan
          </router-link>
        </li>
        <li class="hover:bg-gray-200 rounded-md">
          <router-link to="/pst/daftar-pelayanan" class="w-full text-center py-4 focus:menu-items" exact-active-class="bg-gray-700 text-slate-100">
            <i class="fa-solid fa-couch fa-lg"></i>
              Daftar Pelayanan
          </router-link>
        </li>
      </ul>
      <button class="btn btn-error mt-auto mx-6 mb-6" @click="logout">Log out</button>
    </div>
  
    <!-- Main Section -->
    <div class="flex flex-col w-full h-full mx-3 lg:ms-0 lg:me-3 overflow-x-auto">
      <div class="flex justify-between my-5">
        <div class="mx-4">
          <div class="text-sm breadcrumbs">
            <ul>
              <li><a>Kepala Bagian</a></li> 
              <li>{{ title }}</li>
            </ul>
          </div>
          <h3 class="text-2xl font-bold ff-lato">{{ title }}</h3>
        </div>

        <div class="flex">
          <div data-theme="light" class="dropdown dropdown-end self-end bg-transparent mx-5">
            <label tabindex="0" class="btn btn-ghost btn-circle drop-shadow-md avatar">
                <div class="w-12 rounded-full bg-white text-5xl">
                  <i class="fa-solid fa-user my-auto"></i>
                </div>
            </label>
          </div>
        </div>

      </div>

      <div class="grow">
        <router-view/>
      </div>
      <footer class="footer mt-4  footer-center p-4 text-base-content">
      <div>
        <p>Copyright © 2023 - All right reserved by MBKM Mandiri - BPS Provinsi Riau & Universitas Riau 2023</p>
      </div>
    </footer>
    </div>
  </div>
</template>

<script setup>
import useAuthStore from '../stores/auth';
import { useRouter } from 'vue-router';

const props = defineProps({
  title: String,
});

const authStore = useAuthStore();
const router = useRouter();

const logout = async () => {
  await authStore.logout();
  router.replace({ name: 'Login' });
}
</script>

<style scoped>
.focus\:menu-items:focus {
    --tw-text-opacity: 1;
    color: rgb(241 245 249 / var(--tw-text-opacity));
    --tw-bg-opacity: 1;
    background-color: rgb(55 65 81 / var(--tw-bg-opacity));
}
</style>