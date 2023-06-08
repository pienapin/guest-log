<template>
  <div class="w-screen h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <div class="card w-[240px] mx-3 my-3 bg-gray-50 drop-shadow-md hidden lg:block">
      <!-- Sidebar Header -->
      <div class="flex my-8 me-3 justify-center">
        <img class="w-10 mx-2" src="@/assets/img/bps-logo.png">
        <a class="uppercase font-medium text-2xl ff-oswald">Guest Log</a>
      </div>

      <!-- Menu -->
      <ul class="menu w-56 p-2">
        <li class="hover:bg-gray-200 rounded-md" :class="{ 'bg-gray-700 text-slate-100 hover:bg-gray-700': title === 'Dashboard' }">
          <router-link to="/admin" class="w-full active:bg-gray-700 text-center py-4">
            <i class="fa-solid fa-house fa-lg"></i>
              Dashboard
          </router-link>
        </li>
        <li class="hover:bg-gray-200 rounded-md" :class="{ 'bg-gray-700 text-slate-100 hover:bg-gray-700': title === 'Daftar Pengunjung' }">
          <router-link to="/admin/daftar-pengunjung" class="w-full active:bg-gray-700 text-center py-4">
            <i class="fa-solid fa-address-book fa-lg"></i>
              Daftar Pengunjung
          </router-link>
        </li>
        <li class="hover:bg-gray-200 rounded-md" :class="{ 'bg-gray-700 text-slate-100 hover:bg-gray-700': title === 'Daftar Kunjungan' }">
          <router-link to="/admin/daftar-kunjungan" class="w-full active:bg-gray-700 text-center py-4">
            <i class="fa-solid fa-building-circle-check fa-lg"></i>
              Daftar Kunjungan
          </router-link>
        </li>
        <li class="hover:bg-gray-200 rounded-md" :class="{ 'bg-gray-700 text-slate-100 hover:bg-gray-700': title === 'Daftar Pengguna' }">
          <router-link to="/admin/daftar-pengguna" class="w-full active:bg-gray-700 text-center py-4">
            <i class="fa-solid fa-users fa-lg"></i>
              Daftar Pengguna
          </router-link>
        </li>
        <li class="hover:bg-gray-200 rounded-md" :class="{ 'bg-gray-700 text-slate-100 hover:bg-gray-700': title === 'Daftar Pelayanan' }">
          <router-link to="/admin/daftar-pelayanan" class="w-full active:bg-gray-700 text-center py-4">
            <i class="fa-solid fa-couch fa-lg"></i>
              Daftar Pelayanan
          </router-link>
        </li>
      </ul>
    </div>
  
    <!-- Main Section -->
    <div class="flex flex-col w-full h-full mx-3 lg:ms-0 lg:me-3 overflow-x-auto">
      <div class="flex justify-between my-5">
        <div class="mx-4">
          <div class="text-sm breadcrumbs">
            <ul>
              <li><a>Admin</a></li> 
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
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
              <li><a class="btn btn-md btn-error">Log out</a></li>
            </ul>
          </div>
        </div>

      </div>

      <div class="grow">
        <router-view/>
      </div>
      <footer class="footer footer-center p-4 text-base-content">
      <div>
        <p>Copyright © 2023 - All right reserved by MBKM Mandiri - BPS Provinsi Riau & Universitas Riau 2023</p>
      </div>
    </footer>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
})
</script>