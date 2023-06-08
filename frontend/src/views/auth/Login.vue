<template>
  <div class="h-screen flex items-center justify-center bg-neutral-100">
    <div class="card py-20 px-10 w-1/2 bg-neutral-50 flex-row drop-shadow-lg">
      <div class="container w-1/2 flex flex-col justify-center items-center">
        <div class="logo w-full d-flex justify-center">
          <img class="block justify-cente w-1/2 mx-auto" src="@/assets/img/bps-logo.png"
            alt="BPS Provinsi Riau">
        </div>
        <div class="title mt-5">
          <h2 class="uppercase italic text-2xl font-bold text-center">
            Buku Tamu Pengenal Wajah
            <br />Badan Pusat Statistik<br />Provinsi Riau</h2>
        </div>
      </div>
      <div class="w-1/2 pl-12 flex items-center justify-center">
          <div class="w-full">
            <h3 class="text-center font-bold text-xl px-20 uppercase">Sign In</h3>
            <form action="" method="POST" class="signin-form">
              <div>
                <label class="label label-text" for="email">Nomor Induk Pegawai</label>
                <input id="nip" type="text" class="input input-bordered input-md w-full" v-model="nip"
                required autofocus>
              </div>
              <button type="submit" class="btn mt-5 w-full">Masuk</button>
            </form>
          </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';


const form = reactive({
  nip: null,
});
</script>