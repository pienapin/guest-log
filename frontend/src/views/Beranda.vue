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

    <div class="mx-52">
      <swiper
          :spaceBetween="30" :centeredSlides="true"
          :autoplay='{
              "delay": 3000,
              "disableOnInteraction": false
            }'
          :pagination='{
              "clickable": true
            }'
          :navigation="true"
          :modules="modules"
          class="mySwiper"
      >
          <swiper-slide>
              <img
                  className="object-fill w-full"
                  src="/bpsriau.jpg"
                  alt="image slide 1"
              />
          </swiper-slide>
          <swiper-slide>
              <img
                  className="object-fill w-full"
                  src="/bpsriau1.jpg"
                  alt="image slide 2"
              />
          </swiper-slide>
      </swiper>
    </div>

  <h3 class="text-center mt-4 px-60 text-lg font-medium">Selamat Datang di BPS Provinsi Riau! BUTEP atau Buku Tamu Elektronik Pengenal Wajah adalah layanan buku tamu elektronik yang menggunakan teknologi Face Recognition untuk memudahkan tamu yang berkunjung. Silahkan lengkapi data tamu sebagai bagian dari tertib administrasi di BPS Provinsi Riau.</h3>

  <div class="w-full flex justify-center mt-5">
    <router-link to="/isi" class="uppercase btn btn-md btn-success text-base-100 md:text-xl max-md:text-lg">Isi Buku Tamu</router-link>
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

<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// Import Swiper styles
import "swiper/css";

import "swiper/css/pagination";

// import required modules
import { Pagination, Autoplay } from "swiper";

export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        return {
            modules: [Pagination, Autoplay],
        };
    },
};
</script>